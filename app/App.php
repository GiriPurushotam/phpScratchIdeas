<?php

declare(strict_types=1);

namespace App;

use App\Contracts\RequestHandlerInterface;
use App\Exceptions\FrameworkException;
use App\Exceptions\InvalidHandlerResponseException;
use App\Http\Response;
use App\Http\ResponseInterface;
use App\Http\ServerRequest;
use App\Http\ServerRequestInterface;
use App\Middleware\MiddlewareHandler;
use App\Routing\Route;
use Config\Container\ContainerInterface;
use Exception;

class App
{
	protected array $routes = [];
	protected ViewRenderer $viewRenderer;
	protected array $globalMiddlewares = [];
	protected ContainerInterface $container;

	public function __construct(ViewRenderer $viewRenderer, ContainerInterface $container)
	{
		$this->viewRenderer = $viewRenderer;
		$this->container = $container;
	}

	public function addRoute(string $method, string $path, callable|array $handler): Route
	{
		$route = new Route($method, $path, $handler);
		$this->routes[] = $route;
		return $route;
	}

	public function add(string $middleware): void
	{
		$this->globalMiddlewares[] = $middleware;
	}

	public function get(string $path, callable|array $handler): Route
	{
		return $this->addRoute('GET', $path, $handler);
		// $route = new Route('GET', $path, $handler);
		// $this->routes['GET'][$path] = $route;
		// return $route;
	}

	public function post(string $path, callable|array $handler): Route
	{
		return $this->addRoute('POST', $path, $handler);
		// $route = new Route('POST', $path, $handler);
		// $this->routes['POST'][$path] = $route;
		// return $route;
	}


	public function dispatch(ServerRequestInterface $request, callable|array $handler): ResponseInterface
	{
		if (is_callable($handler)) {
			$result = call_user_func($handler, $request, new Response());
		} elseif (is_array($handler)) {
			[$class, $method] = $handler;

			if (!class_exists($class)) {
				throw new FrameworkException("Controller Class [$class] Not Found");
			}

			$controller = $this->container->get($class);
			if (!method_exists($controller, $method)) {
				throw new FrameworkException(" method [$method] not found in controller [$class]");
			}

			$result = $controller->$method($request, new Response());
		} else {
			throw new Exception("Invalid Handler");
		}

		// var_dump($result);
		if (!$result instanceof ResponseInterface) {
			$type = is_object($result) ? get_class($result) : gettype($result);
			throw new InvalidHandlerResponseException("Handler must return instance of ResponseInterface", "  [$type]");
		}

		return $result;
	}




	public function runMiddleware(array $middlewares, callable $finalHandler): void
	{
		$request = new ServerRequest();

		$final = new class($finalHandler) implements RequestHandlerInterface {
			private $finalHandler;
			public function __construct(callable $finalHandler)
			{
				$this->finalHandler = $finalHandler;
			}

			public function handle(ServerRequestInterface $request): ResponseInterface
			{
				$response =  call_user_func($this->finalHandler, $request);
				if (!$response instanceof ResponseInterface) {
					$type = is_object($response) ? get_class($response) : gettype($response);
					throw new InvalidHandlerResponseException("Final handler must return instance of ResponseInterface, got:",  gettype($type));
				}

				return $response;
			}
		};


		$handler = new MiddlewareHandler($middlewares, $final, $this->container);

		$response = $handler->handle($request);

		if ($response instanceof ResponseInterface) {
			$response->send();
		} else {
			throw new \Exception("Middleware chain did not return a valid ResponseInterface");
		}
	}

	public function run(): void
	{


		$method = $_SERVER['REQUEST_METHOD'];
		$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
		$script_name = dirname($_SERVER["SCRIPT_NAME"]);

		if (str_starts_with($uri, $script_name)) {
			$uri = substr($uri, strlen($script_name));
		}

		$uri = '/' . trim($uri, '/');

		foreach ($this->routes as $route) {
			if ($route->method === $method && $route->path === $uri) {
				$finalHandler = fn(ServerRequestInterface $request) => $this->dispatch($request, $route->handler);
				$middlewares = array_merge($this->globalMiddlewares, $route->middlewares);
				$this->runMiddleware($middlewares, $finalHandler);
				// $this->dispatch($route['handler']);
				return;
			}
		}

		$response = new Response();
		$response->withJson(['error' => '404 Not Found'], 404)->send();

		// http_response_code(404);
		// echo "404 Not Found";
	}
}

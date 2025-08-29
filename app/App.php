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
use App\Routing\RouteCollectorProxy;
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

	public function group(string $prefix, callable $callBack, array $middlewares = []): RouteCollectorProxy
	{

		$proxy = new RouteCollectorProxy($this, $prefix, $middlewares);
		$callBack($proxy);
		return $proxy;
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

	public function delete(string $path, callable|array $handler): Route
	{

		return $this->addRoute('DELETE', $path, $handler);
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


	public function dispatchWithArgs(ServerRequestInterface $request, callable|array $handler, array $args): ResponseInterface
	{
		if (is_callable($handler)) {
			return call_user_func($handler, $request, new Response(), $args);
		} elseif (is_array($handler)) {
			[$class, $method] = $handler;

			if (!class_exists($class)) {
				throw new FrameworkException("Controller class [$class] Not Found");
			}

			$controller = $this->container->get($class);
			if (!method_exists($controller, $method)) {
				throw new FrameworkException("Method [$method] Not Found In Controller [$class]");
			}

			return $controller->$method($request, new Response(), $args);
		}

		throw new Exception("Invalid Handler");
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
		// Step 1: Create the request object
		$request = new ServerRequest();

		// Step 2: Prepare final handler (after middleware)
		$finalHandler = function (ServerRequestInterface $request): ResponseInterface {
			// Now method is already overridden by MethodOverrideMiddleware
			$method = $request->getMethod();
			$uri = parse_url($request->getServerParams()['REQUEST_URI'], PHP_URL_PATH);

			$script_name = dirname($request->getServerParams()['SCRIPT_NAME'] ?? '');
			if (str_starts_with($uri, $script_name)) {
				$uri = substr($uri, strlen($script_name));
			}
			$uri = '/' . trim($uri, '/');

			// Step 3: Match route
			foreach ($this->routes as $route) {
				// Convert {param} to regex for dynamic routes
				$pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route->path);
				$pattern = '#^' . $pattern . '$#';

				if ($route->method === $method && preg_match($pattern, $uri, $matches)) {
					array_shift($matches); // remove full match
					$args = [];

					// extract param names
					if (preg_match_all('#\{([^/]+)\}#', $route->path, $paramNames)) {
						foreach ($paramNames[1] as $index => $name) {
							$args[$name] = $matches[$index];
						}
					}

					// Route-specific handler
					$routeHandler = fn(ServerRequestInterface $request) =>
					$this->dispatchWithArgs($request, $route->handler, $args);

					// Run route + global + route-specific middlewares
					$middlewares = array_merge($this->globalMiddlewares, $route->middlewares);
					$this->runMiddleware($middlewares, $routeHandler);
					return new Response(); // should never reach here
				}
			}

			// Step 4: No route matched → 404
			$response = new Response();
			return $response->withJson(['error' => '404 Not Found'], 404);
		};

		// Step 5: Run global middlewares
		$handler = new MiddlewareHandler(
			$this->globalMiddlewares,
			new class($finalHandler) implements RequestHandlerInterface {
				private $finalHandler;
				public function __construct(callable $finalHandler)
				{
					$this->finalHandler = $finalHandler;
				}
				public function handle(ServerRequestInterface $request): ResponseInterface
				{
					$response = call_user_func($this->finalHandler, $request);
					if (!$response instanceof ResponseInterface) {
						throw new \Exception("Final handler must return ResponseInterface");
					}
					return $response;
				}
			},
			$this->container
		);

		// Step 6: Execute middleware chain
		$response = $handler->handle($request);
		$response->send();
	}
}

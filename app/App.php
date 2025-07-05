<?php

declare(strict_types=1);

namespace App;

use App\Routing\Route;
use Exception;

class App {
	protected array $routes = [];
	protected ViewRenderer $viewRenderer;

	public function __construct(ViewRenderer $viewRenderer)
	{
		$this->viewRenderer = $viewRenderer;
	}

	public function addRoute(string $method, string $path, callable|array $handler): Route{
		$route = new Route($method, $path, $handler);
		$this->routes[] = $route;
		return $route;
	}

	public function get(string $path, callable|array $handler): Route {
		return $this->addRoute('GET', $path, $handler);
		// $route = new Route('GET', $path, $handler);
		// $this->routes['GET'][$path] = $route;
		// return $route;
	}

	public function post(string $path, callable|array $handler): Route {
		return $this->addRoute('POST', $path, $handler);
		// $route = new Route('POST', $path, $handler);
		// $this->routes['POST'][$path] = $route;
		// return $route;
	}

	public function dispatch(callable|array $handler): void {
		if(is_callable($handler)) {
			call_user_func($handler);
		}elseif (is_array($handler)) {
			[$class, $method] = $handler;
			if(class_exists($class)) {
				$controller = new $class($this->viewRenderer);
				if(method_exists($controller, $method)) {
				$result = $controller->$method();
				if(is_string($result)) {
					echo $result;
				}elseif (is_array($result)) {
					header('Content-Type: application/json');
					echo json_encode($result);
				} else {
					throw new \Exception("Unsupported return type from $class::$method");
				}
				}else {
					throw new \Exception("Method $method Has Not found in Controller $class");
				}
			} else {
				throw new  \Exception("Controller Class $class Has Not Found");
			}
		}
	}

	public function runMiddleware(array $middlewares, callable $finalHandler): void {
		$middleware = array_shift($middlewares);
		if($middleware === null) {
			$finalHandler();
			return;
		}

		$instance = new $middleware();
		if(!method_exists($instance, 'handle')) {
			throw new Exception("Middleware Must Have handle Method");
		}

		$instance->handle(function () use ($middlewares, $finalHandler) {
			$this->runMiddleware($middlewares, $finalHandler);
		});
	}

	public function run(): void {
		$method = $_SERVER['REQUEST_METHOD'];
		$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
		$script_name = dirname($_SERVER["SCRIPT_NAME"]);

		if(str_starts_with($uri, $script_name)) {
			$uri = substr($uri, strlen($script_name));

		}

		$uri = '/' . trim($uri, '/');

		foreach($this->routes as $route) {
			if($route->method === $method && $route->path === $uri){
				$finalHandler = fn() => $this->dispatch($route->handler);
				$this->runMiddleware($route->middlewares, $finalHandler);
				// $this->dispatch($route['handler']);
				return;

			}
		}

		http_response_code(404);
		echo "404 Not Found";
	}
}
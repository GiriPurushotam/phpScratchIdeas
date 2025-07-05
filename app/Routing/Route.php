<?php

declare(strict_types=1);

namespace App\Routing;

class Route {
	public  $method;
	public  $path;
	public $handler;
	public  $middlewares = [];

	public function __construct($method,  $path, $handler)
	{
		$this->method = $method;
		$this->path = $path;
		$this->handler = $handler;
	}

	// public function getMethod(): string {
	// 	return $this->method;
	// }

	// public function getPath(): string {
	// 	return $this->path;
	// }

	// public function getHandler(): array {
	// 	return $this->handler;
	// }

	// public function getMiddleware(): array {
	// 	return $this->middleware;
	// }

	public function add(string $middleware): self {
		$this->middlewares[] = $middleware;
		return $this;
	}
}
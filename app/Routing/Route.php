<?php

declare(strict_types=1);

namespace App\Routing;

class Route
{
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

	public function add(string $middleware): self
	{
		$this->middlewares[] = $middleware;
		return $this;
	}
}

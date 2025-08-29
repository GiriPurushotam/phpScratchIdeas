<?php

declare(strict_types=1);

namespace App\Routing;

use App\App;

class RouteCollectorProxy
{

    private App $app;
    private string $prefix = '';
    private array $groupMiddlewares = [];

    public function __construct(App $app, string $prefix = '', array $middlewares = [])
    {

        $this->app = $app;
        $this->prefix = $prefix;
        $this->groupMiddlewares = $middlewares;
    }


    public function get(string $path, $handler): self
    {

        $fullPath = $this->prefix . ($path === '' ? '' :  '/' . ltrim($path, '/'));

        var_dump($this->prefix, $path, $fullPath, $handler);

        $route = $this->app->get($fullPath, $handler);

        foreach ($this->groupMiddlewares as $middleware) {
            $route->add($middleware);
        }

        return $this;
    }

    public function post(string $path, $handler): self
    {

        $fullPath = $this->prefix . ($path === '' ? '' : '/' . ltrim($path, '/'));
        $route = $this->app->post($fullPath, $handler);

        foreach ($this->groupMiddlewares as $middleware) {
            $route->add($middleware);
        }

        return $this;
    }


    public function delete(string $path, $handler): self
    {

        $fullPath = $this->prefix . ($path === '' ? '' : '/' . ltrim($path, '/'));
        $route = $this->app->delete($fullPath, $handler);

        foreach ($this->groupMiddlewares as $middleware) {
            $route->add($middleware);
        }

        return $this;
    }
}

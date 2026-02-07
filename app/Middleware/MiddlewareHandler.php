<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\RequestHandlerInterface;
use App\Http\ServerRequestInterface;
use App\Http\ResponseInterface;
use Config\Container\ContainerInterface;

class MiddlewareHandler implements RequestHandlerInterface
{

    private array $middlewares;
    private RequestHandlerInterface $finalHandler;
    private ContainerInterface $container;

    public function __construct(array $middlewares, RequestHandlerInterface $finalHandler, ContainerInterface $container)
    {
        $this->middlewares = $middlewares;
        $this->finalHandler = $finalHandler;
        $this->container = $container;
    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        if (empty($this->middlewares)) {
            $result = $this->finalHandler->handle($request);

            if (!$result instanceof ResponseInterface) {
                throw new \RuntimeException("final handler returned invalid  type:" . gettype($result));
            }

            return $result;
        }

        $middleware = array_shift($this->middlewares);
        $instance = $this->container->get($middleware);

        if (!method_exists($instance, 'process')) {
            throw new \RuntimeException("Middleware must implement process() method");
        }

        return $instance->process($request, new self($this->middlewares, $this->finalHandler, $this->container));
    }
}

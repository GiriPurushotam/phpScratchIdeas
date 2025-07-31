<?php

use Config\Container\ContainerInterface;

if (!function_exists('create')) {
    function create(string $class): object
    {
        return new class($class) {

            private string $class;
            private array $args = [];

            public function __construct(string $class)
            {
                $this->class = $class;
            }

            public function constructor(mixed ...$argc): Closure
            {

                return fn(ContainerInterface $c) => new $this->class(...$argc);
            }
        };
    }
}

if (!function_exists('get')) {
    function get(string $class): Closure
    {
        return fn(ContainerInterface $c) => $c->get($class);
    }
}

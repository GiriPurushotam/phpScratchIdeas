<?php

declare(strict_types=1);

namespace Config\Container;

class Definitions
{

    private string $class;
    private array $args = [];

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function constructor(mixed ...$argc): self
    {
        $this->args = $argc;
        return $this;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getArguments(): array
    {

        return $this->args;
    }
}

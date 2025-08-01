<?php

declare(strict_types=1);

namespace App\Http;

use App\Http\ServerRequestInterface;

class ServerRequest implements ServerRequestInterface
{
    protected array $get;
    protected array $post;
    protected array $server;
    private array $attributes = [];

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
    }
    public function getParsedBody(): array
    {
        return $this->post;
    }

    public function getQueryParams(): array
    {
        return $this->get;
    }

    public function getMethod(): string
    {
        return strtoupper($this->server['REQUEST_METHOd'] ?? 'GET');
    }

    public function getUri(): string
    {
        return $this->server['REQUEST_URI'] ?? '/';
    }

    public function withAttribute(string $name, mixed $value): static
    {
        $clone = clone $this;
        $clone->attributes[$name] = $value;
        return $clone;
    }

    public function getAttribute(string $name, mixed $default = null): mixed
    {

        return $this->attributes[$name] ?? $default;
    }

    public function getAttributes(): array
    {

        return $this->attributes;
    }
}

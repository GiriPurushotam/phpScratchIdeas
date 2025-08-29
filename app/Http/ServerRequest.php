<?php

declare(strict_types=1);

namespace App\Http;

use App\Http\ServerRequestInterface;

class ServerRequest implements ServerRequestInterface
{
    protected array $get;
    protected array $post;
    protected array $server;
    protected array $headers = [];
    private array $attributes = [];
    private StreamInterface $body;
    private string $method;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->method = strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');


        $this->parseHeaders();

        $this->body = new PhpInputStream();
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
        return $this->method;
    }

    public function withMethod(string $method): static
    {
        $clone = clone $this;
        $clone->method = strtoupper($method);
        return $clone;
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

    public function getServerParams(): array
    {
        return $this->server;
    }

    public function getHeader(string $name): array
    {

        $normalized = strtolower($name);
        return $this->headers[$normalized] ?? [];
    }

    public function hasHeader(string $name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeaderLine(string $name): string
    {

        $values = $this->getHeader($name);

        return $values ? implode(', ', $values) : '';
    }

    public function parseHeaders(): void
    {

        foreach ($this->server as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headerName = strtolower(str_replace('_', '-', substr($key, 5)));
                $this->headers[$headerName] = [$value];
            }
        }

        if (isset($this->server['CONTENT-TYPE'])) {
            $this->headers['CONTENT-TYPE'] = [$this->server['CONTENT-TYPE']];
        }

        if (isset($this->server['CONTENT-LENGTH'])) {
            $this->headers['CONTENT-LENGTH'] = [$this->server['CONTENT-LENGTH']];
        }
    }


    public function getBody(): StreamInterface
    {

        return $this->body;
    }
}

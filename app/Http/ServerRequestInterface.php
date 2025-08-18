<?php

declare(strict_types=1);

namespace App\Http;

interface ServerRequestInterface
{
    // return all post data //

    public function getParsedBody(): array;

    // return get query params // 

    public function getQueryParams(): array;

    // http methods in uppercase GET POST ETC //
    public function getMethod(): string;

    // raw request uri including path and query //

    public function getUri(): string;

    public function withAttribute(string $name, mixed $value): static;

    public function getAttribute(string $name, mixed $default = null): mixed;

    public function getServerParams(): array;

    public function getHeader(string $name): array;

    public function hasHeader(string $name): bool;

    public function getHeaders(): array;

    public function parseHeaders(): void;
}

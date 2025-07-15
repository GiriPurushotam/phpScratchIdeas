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
}

<?php

declare(strict_types=1);

namespace App\Http;

use App\Http\ServerRequestInterface;

class ServerRequest implements ServerRequestInterface
{
    protected array $get;
    protected array $post;
    protected array $server;

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
}

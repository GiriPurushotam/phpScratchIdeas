<?php

declare(strict_types=1);

namespace App\Http;

use App\Formatter\Stream;

interface ResponseInterface
{


    // send a json response and exit //
    public function withJson(array $data, int $status = 200): self;


    //redirect to another url and exit //
    public function redirect(string $url, int $status = 302): self;


    // write raw html text and exit //

    public function write(string $content): self;

    public function getBody(): Stream;

    public function send(): void;

    public function withStatus(int $status): self;

    public function withHeader(string $name, string $value): self;
}

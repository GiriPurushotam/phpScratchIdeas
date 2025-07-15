<?php

declare(strict_types=1);

namespace App\Http;

interface ResponseInterface
{


    // send a json response and exit //
    public function withJson(array $data, int $status = 200): void;


    //redirect to another url and exit //
    public function redirect(string $url, int $status = 302): void;


    // write raw html text and exit //

    public function write(string $content): void;
}

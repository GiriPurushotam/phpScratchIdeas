<?php

declare(strict_types=1);

namespace App\Http;

class Response implements ResponseInterface
{

    public function withJson(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-type: application/json');
        echo json_encode($data);
        exit;
    }


    public function redirect(string $url, int $status = 302): void
    {
        http_response_code($status);
        header("Location: $url");
        exit;
    }


    public function write(string $content): void
    {
        echo $content;
        exit;
    }

    public function send(): void {}
}

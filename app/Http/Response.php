<?php

declare(strict_types=1);

namespace App\Http;

class Response implements ResponseInterface
{

    protected string $content = '';
    protected array $headers = [];
    protected int $statusCode = 200;
    protected ?string $redirectUrl = null;
    protected bool $isJson = false;

    public function __construct(int $statusCode = 200)
    {
        $this->statusCode = $statusCode;
    }


    public function withJson(array $data, int $status = 200): self
    {
        $this->isJson = true;
        $this->statusCode = $status;
        $this->headers['Content-Type'] = 'application/json';
        $this->content = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $this;
    }


    public function redirect(string $url, int $status = 302): self
    {
        $this->redirectUrl = $url;
        $this->statusCode = $status;
        return $this;
    }


    public function write(string $content): ResponseInterface
    {
        $this->content .= $content;
        if (!isset($this->headers['Content-Type'])) {
            $this->headers['Content-Type'] = 'text/html; charset=UTF-8';
        }
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        if ($this->redirectUrl !== null) {
            header("Location: {$this->redirectUrl}", true, $this->statusCode);
        }

        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        echo $this->content;
        exit;
    }

    public function withStatus(int $status): ResponseInterface
    {
        $this->statusCode = $status;
        return $this;
    }

    public function withHeader(string $name, string $value): ResponseInterface
    {
        $this->headers[$name] = $value;
        return $this;
    }
}

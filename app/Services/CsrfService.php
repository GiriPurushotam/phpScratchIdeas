<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ResponseFactoryInterface;
use App\Contracts\SessionInterface;
use App\Http\ServerRequestInterface;

class CsrfService
{

    private string $tokenNameKey = 'csrf_name';
    private string $tokenValueKey = 'csrf_value';
    private bool $persistentTokenMode;


    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        bool $persistentTokenMode = true
    ) {

        $this->persistentTokenMode = $persistentTokenMode;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!$this->persistentTokenMode || !$this->hasToken()) {
            $this->generateTokens();
        }
    }

    public function hasToken(): bool
    {
        return isset($_SESSION[$this->tokenNameKey], $_SESSION[$this->tokenValueKey]);
    }

    public function generateTokens(): void
    {
        $_SESSION[$this->tokenNameKey] = bin2hex(random_bytes(16));
        $_SESSION[$this->tokenValueKey] = bin2hex(random_bytes(16));
    }

    public function getTokenNameKey(): string
    {
        return $this->tokenNameKey;
    }

    public function getTokenValueKey(): string
    {

        return $this->tokenValueKey;
    }

    public function getTokenName(): string
    {

        return $_SESSION[$this->tokenNameKey];
    }

    public function getTokenValue(): string
    {

        return $_SESSION[$this->tokenValueKey];
    }

    public function validate(string $name, string $value): bool
    {
        return $name === $this->getTokenName() && hash_equals($this->getTokenValue(), $value);
    }

    public function validateRequest(ServerRequestInterface $request): void
    {
        if (!in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])) {
            return;
        }

        $data = $request->getParsedBody() ?? [];


        $name = $data[$this->getTokenNameKey()] ?? null;
        $value = $data[$this->getTokenValueKey()] ?? null;

        if (!$name || !$value || !$this->validate($name, $value)) {
            throw new \RuntimeException(' : CSRF Validation Failed');
        }

        if (!$this->persistentTokenMode) {
            $this->generateTokens();
        }
    }
}

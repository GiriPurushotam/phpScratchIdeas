<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\MiddlewareInterface;
use App\Http\ServerRequestInterface;
use App\Contracts\RequestHandlerInterface;
use App\Http\ResponseInterface;
use App\Services\CsrfService;

class CsrfMiddleware implements MiddlewareInterface
{

    public function __construct(private readonly CsrfService $csrf) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // die('csrf middleware hit');

        $this->csrf->validateRequest($request);

        return $handler->handle($request);
    }
}

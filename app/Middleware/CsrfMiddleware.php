<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\MiddlewareInterface;
use App\Http\ServerRequestInterface;
use App\Contracts\RequestHandlerInterface;
use App\Contracts\ResponseFactoryInterface;
use App\Http\ResponseInterface;
use App\Services\CsrfService;

class CsrfMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly CsrfService $csrf,
        private readonly ResponseFactoryInterface $responseFactory
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $this->csrf->validateRequest($request);
        } catch (\RuntimeException $e) {
            return $this->responseFactory
                ->createResponse(403)
                ->withBody(json_encode(['error' => 'csrf validation failed']), 'application/json');
        }

        return $handler->handle($request);
    }
}

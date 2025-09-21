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
        // die('csrf middleware hit');

        try {
            $this->csrf->validateRequest($request);
        } catch (\RuntimeException $e) {

            return $this->responseFactory->createResponse(403)->withHeader('Content-Type', 'application/json')->write(
                json_encode(['error' => 'CSRF validation failed'])
            );
        }


        return $handler->handle($request);
    }
}

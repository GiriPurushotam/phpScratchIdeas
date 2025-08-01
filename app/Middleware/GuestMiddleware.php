<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\MiddlewareInterface;
use App\Http\ServerRequestInterface;
use App\Contracts\RequestHandlerInterface;
use App\Contracts\ResponseFactoryInterface;
use App\Contracts\SessionInterface;
use App\Http\ResponseInterface;

class GuestMiddleware implements MiddlewareInterface
{

    public function __construct(private readonly ResponseFactoryInterface $responseFactory, private readonly SessionInterface $session) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->session->get('user')) {
            return $this->responseFactory->createResponse(302)->redirect(BASE_PATH . '/');
        }

        return $handler->handle($request);
    }
}

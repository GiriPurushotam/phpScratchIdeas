<?php

declare(strict_types=1);

namespace App\Middleware;

use SessionHandler;
use App\Http\ResponseInterface;
use App\Contracts\SessionInterface;
use App\Exceptions\SessionException;
use App\Http\ServerRequestInterface;
use App\Contracts\MiddlewareInterface;
use App\Contracts\RequestHandlerInterface;
use App\Services\RequestService;

class StartSessionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly SessionInterface $session,
        private readonly RequestService $requestService
    ) {}


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (session_status() === PHP_SESSION_NONE) {
            $this->session->start();
        }

        $response = $handler->handle($request);

        //TODO: check for XHR requests

        if (strtoupper($request->getMethod()) === 'GET' && ! $this->requestService->isXhr($request)) {

            $this->session->put('previousUrl',  $request->getUri());
        }

        $this->session->save();

        return $response;
    }
}

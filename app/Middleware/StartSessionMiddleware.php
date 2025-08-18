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

class StartSessionMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly SessionInterface $session) {}


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->session->start();

        $response = $handler->handle($request);

        //TODO: check for XHR requests

        if ($request->getMethod() === 'GET') {

            $this->session->put('previousUrl',  $request->getUri());
        }

        $this->session->save();

        return $response;
    }
}

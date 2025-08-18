<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\AuthInterface;
use App\Contracts\MiddlewareInterface;
use App\Http\ServerRequestInterface;
use App\Contracts\RequestHandlerInterface;
use App\Http\ResponseInterface;
use App\ViewRenderer;

class AuthenticateMiddleware implements MiddlewareInterface
{

    public function __construct(private readonly AuthInterface $auth, private readonly ViewRenderer $view) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($user = $this->auth->user()) {

            $this->view->getEnvironment()->addGlobal('userData', $user);
        }

        return $handler->handle($request);
    }
}

<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\MiddlewareInterface;
use App\Http\ServerRequestInterface;
use App\Contracts\RequestHandlerInterface;
use App\Contracts\SessionInterface;
use App\Http\ResponseInterface;
use App\ViewRenderer;

class OldFormDataMiddleware implements MiddlewareInterface
{

    public function __construct(
        private readonly ViewRenderer $view,
        private readonly SessionInterface $session
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        if ($old = $this->session->getFlash('old')) {
            // $old = $_SESSION['old'];

            $this->view->getEnvironment()->addGlobal('old', $old);
            // unset($_SESSION['old']);
        }

        return $handler->handle($request);
    }
}

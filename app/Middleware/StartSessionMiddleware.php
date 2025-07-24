<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Http\ResponseInterface;
use App\Exceptions\SessionException;
use App\Http\ServerRequestInterface;
use App\Contracts\MiddlewareInterface;
use App\Contracts\RequestHandlerInterface;
use SessionHandler;

class StartSessionMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (session_start() === PHP_SESSION_ACTIVE) {
            throw new SessionException("Session already started");
        }

        if (headers_sent($fileName, $line)) {
            throw new SessionException("Header has been already sent in [$fileName] on line [$line] ");
        }

        session_set_cookie_params(['secure' => true, 'httponly' => true, 'samesite' => 'lax']);

        session_start();

        $response = $handler->handle($request);

        return $response;
    }
}

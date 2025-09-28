<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\MiddlewareInterface;
use App\Http\ServerRequestInterface;
use App\Contracts\RequestHandlerInterface;
use App\Http\ResponseInterface;

class BodyParsingMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $contentType = $request->getHeaderLine('content-type');

        if (str_contains($contentType, 'application/json')) {
            $body = $request->getBody();
            $body->rewind();
            $data = json_decode((string) $body, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                $request = $request->withParsedBody($data);
            }
        }

        return $handler->handle($request);
    }
}

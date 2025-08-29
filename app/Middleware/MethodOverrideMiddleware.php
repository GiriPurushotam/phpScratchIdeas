<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\MiddlewareInterface;
use App\Http\ServerRequestInterface;
use App\Contracts\RequestHandlerInterface;
use App\Http\ResponseInterface;

class MethodOverrideMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $methodHeader = $request->getHeaderLine('X-Http-Method-Override');



        if ($methodHeader) {
            $request = $request->withMethod($methodHeader);
        } elseif (strtoupper($request->getMethod()) === 'POST') {
            $body = $request->getParsedBody();


            if (is_array($body) && !empty($body['_METHOD'])) {
                $request = $request->withMethod($body['_METHOD']);
            }

            if ($request->getBody()->eof()) {
                $request->getBody()->rewind();
            }
        }



        // echo '<pre>';
        // var_dump($request->getMethod());
        // echo '</pre>';
        // exit;



        return $handler->handle($request);
    }
}

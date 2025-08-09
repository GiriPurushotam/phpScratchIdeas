<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Http\ResponseInterface;
use App\Http\ServerRequestInterface;
use App\Contracts\MiddlewareInterface;
use App\Exceptions\ValidationException;
use App\Contracts\RequestHandlerInterface;
use App\Contracts\ResponseFactoryInterface;

class ValidationExceptionMiddleware implements MiddlewareInterface
{

    public function __construct(private readonly ResponseFactoryInterface $responseFactory) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        try {
            return $handler->handle($request);
        } catch (ValidationException $e) {
            $response = $this->responseFactory->createResponse();
            $referer = $request->getServerParams()['HTTP_REFERER'];
            $oldData = $request->getParsedBody();

            $sensetiveFields = ['password', 'confirmPassword'];

            $_SESSION['errors'] = $e->errors;
            $_SESSION['old'] = array_diff_key($oldData, array_flip($sensetiveFields));

            return $response->withHeader('Location', $referer)->withStatus(302);
        }
    }
}

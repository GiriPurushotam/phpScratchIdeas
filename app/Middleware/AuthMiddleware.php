<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\MiddlewareInterface;
use App\Http\ServerRequestInterface;
use App\Contracts\RequestHandlerInterface;
use App\Contracts\ResponseFactoryInterface;
use App\Http\ResponseInterface;

class AuthMiddleware implements MiddlewareInterface
{

	public function __construct(private ResponseFactoryInterface $responseFactory) {}


	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		if (empty($_SESSION['user'])) {

			return $this->responseFactory->createResponse(302)->redirect(BASE_PATH . '/login');
		}

		return $handler->handle($request);
	}
}

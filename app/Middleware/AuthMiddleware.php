<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\AuthInterface;
use App\Contracts\MiddlewareInterface;
use App\Http\ServerRequestInterface;
use App\Contracts\RequestHandlerInterface;
use App\Contracts\ResponseFactoryInterface;
use App\Http\ResponseInterface;

class AuthMiddleware implements MiddlewareInterface
{

	public function __construct(private readonly ResponseFactoryInterface $responseFactory, private readonly AuthInterface $auth) {}


	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		if ($user = $this->auth->user()) {

			// return $handler->handle($request->withAttribute('user', $user));

			$request = $request->withAttribute('user', $user);
			var_dump($request->getAttribute('user'));
			return $handler->handle($request);
		}

		return $this->responseFactory->createResponse(302)->redirect(BASE_PATH . '/login');
	}
}

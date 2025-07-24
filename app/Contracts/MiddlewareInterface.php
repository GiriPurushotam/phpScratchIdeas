<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Http\ResponseInterface;
use App\Http\ServerRequestInterface;

interface MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}

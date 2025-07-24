<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Http\ResponseInterface;
use App\Http\ServerRequestInterface;

interface RequestHandlerInterface
{


    public function handle(ServerRequestInterface $request): ResponseInterface;
}

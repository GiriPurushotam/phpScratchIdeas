<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Http\ResponseInterface;

interface ResponseFactoryInterface
{


    public function createResponse(int $code = 200): ResponseInterface;
}

<?php

declare(strict_types=1);

namespace App\Factory;

use App\Contracts\ResponseFactoryInterface;
use App\Http\Response;
use App\Http\ResponseInterface;

class ResponseFactory implements ResponseFactoryInterface
{

    public function createResponse(int $code = 200): ResponseInterface
    {
        http_response_code($code);

        return new Response();
    }
}

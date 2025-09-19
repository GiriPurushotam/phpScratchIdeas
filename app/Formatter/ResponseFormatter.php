<?php

declare(strict_types=1);

namespace App\Formatter;

use App\Http\ResponseInterface;

class ResponseFormatter
{

    public function asJson(ResponseInterface $response, mixed $data, int $flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS | JSON_THROW_ON_ERROR)
    {
        $json = json_encode($data, $flags);

        return  $response->withHeader('Content-Type', 'application/json')->write($json);
    }
}

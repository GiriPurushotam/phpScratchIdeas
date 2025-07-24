<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Http\ResponseInterface;

class InvalidHandlerResponseException extends FrameworkException
{

    public function __construct(
        string $handlerDescription,
        string $actualType,
        string $expectedInterface = 'App\Http\ResponseInterface'
    ) {
        $message = "Handler `{$handlerDescription}` must return instance of {$expectedInterface}, got {$actualType}.";

        parent::__construct($message, [
            'handler' => $handlerDescription,
            'expected' => $expectedInterface,
            'actual' => $actualType,
        ]);
    }
}

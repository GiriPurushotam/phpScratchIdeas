<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\MiddlewareInterface;
use App\Http\ServerRequestInterface;
use App\Contracts\RequestHandlerInterface;
use App\Http\ResponseInterface;
use App\Services\CsrfService;
use App\ViewRenderer;
use Config\Container\ContainerInterface;

class CsrfFieldMiddleware implements MiddlewareInterface
{

    public function __construct(
        private readonly CsrfService $csrf,
        private readonly ViewRenderer $view,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {




        $csrf = $this->csrf;
        $csrf->validateRequest($request);

        $fields = <<<CSRF_FIELDS
<input type="hidden" name="{$this->csrf->getTokenNameKey()}" value="{$this->csrf->getTokenName()}">
<input type="hidden" name="{$this->csrf->getTokenValueKey()}" value="{$this->csrf->getTokenValue()}">
CSRF_FIELDS;

        $this->view->getEnvironment()->addGlobal('csrf', [
            'keys' => [
                'name' => $this->csrf->getTokenNameKey(),
                'value' => $this->csrf->getTokenValueKey(),

            ],

            'name' => $this->csrf->getTokenName(),
            'value' => $this->csrf->getTokenValue(),
            'fields' => $fields,
        ]);

        return $handler->handle($request);
    }
}

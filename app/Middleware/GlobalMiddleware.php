<?php

declare(strict_types=1);

use App\App;
use App\Middleware\AuthenticateMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfFieldMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\OldFormDataMiddleware;
use App\Middleware\StartSessionMiddleware;
use App\Middleware\ValidationErrorsMiddleware;
use App\Middleware\ValidationExceptionMiddleware;

return function (App $app) {

    $app->add(StartSessionMiddleware::class);
    $app->add(CsrfMiddleware::class);
    $app->add(CsrfFieldMiddleware::class);
    $app->add(ValidationExceptionMiddleware::class);
    $app->add(ValidationErrorsMiddleware::class);
    $app->add(OldFormDataMiddleware::class);
    $app->add(AuthenticateMiddleware::class);
};

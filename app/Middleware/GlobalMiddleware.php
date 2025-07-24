<?php

declare(strict_types=1);

use App\App;
use App\Middleware\AuthMiddleware;
use App\Middleware\StartSessionMiddleware;

return function (App $app) {

    $app->add(StartSessionMiddleware::class);
};

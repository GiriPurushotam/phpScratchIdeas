<?php

declare(strict_types=1);

use App\App;
use App\Middleware\AuthMiddleware;

return function (App $app) {
    $app->add(AuthMiddleware::class);
};

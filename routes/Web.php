<?php

declare(strict_types=1);

namespace App\App;

use App\App;
use App\Controller\TestController;
use App\ViewRenderer;
use App\Middleware\AuthMiddleware;

// $viewRenderer = new ViewRenderer(VIEW_PATH);
// $app = new App($viewRenderer);

// $app->get('/', [TestController::class, 'index']);
// $app->get('/json', [TestController::class, 'json']);

// $app->run();

return function(App $app) {
	$app->get('/', [TestController::class, 'index'])->add(AuthMiddleware::class);
	$app->get('/json', [TestController::class, 'json']);
};
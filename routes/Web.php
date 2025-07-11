<?php

declare(strict_types=1);

namespace App\App;

use App\App;
use App\Controller\TestController;
use App\ViewRenderer;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

// $viewRenderer = new ViewRenderer(VIEW_PATH);
// $app = new App($viewRenderer);

// $app->get('/', [TestController::class, 'index']);
// $app->get('/json', [TestController::class, 'json']);

// $app->run();

return function (App $app) {
	$app->get('/', [TestController::class, 'index']);
	$app->get('/json', [TestController::class, 'json'])->add(AuthMiddleware::class);
	$app->get('/login', [TestController::class, 'loginView'])->add(GuestMiddleware::class);
	$app->get('/register', [TestController::class, 'registerView'])->add(GuestMiddleware::class);

	$app->post('/login', [TestController::class, 'login']);
	$app->post('/register', [TestController::class, 'register']);
};

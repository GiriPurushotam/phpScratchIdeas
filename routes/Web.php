<?php

declare(strict_types=1);

namespace App\App;

use App\App;
use App\Controller\CategoriesController;
use App\Controller\TestController;
use App\ViewRenderer;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\StartSessionMiddleware;

// $viewRenderer = new ViewRenderer(VIEW_PATH);
// $app = new App($viewRenderer);

// $app->get('/', [TestController::class, 'index']);
// $app->get('/json', [TestController::class, 'json']);

// $app->run();

return function (App $app) {
	$app->get('/', [TestController::class, 'index'])->add(AuthMiddleware::class);
	$app->get('/json', [TestController::class, 'json'])->add(AuthMiddleware::class);
	$app->get('/login', [TestController::class, 'loginView'])->add(GuestMiddleware::class);
	$app->get('/register', [TestController::class, 'registerView'])->add(GuestMiddleware::class);
	$app->get('/test', [TestController::class, 'test']);
	$app->post('/login', [TestController::class, 'login'])->add(GuestMiddleware::class);
	$app->post('/register', [TestController::class, 'register'])->add(GuestMiddleware::class);
	$app->post('/logout', [TestController::class, 'logout']);
	$app->get('/categories', [CategoriesController::class, 'index'])->add(AuthMiddleware::class);
	$app->post('/categories', [CategoriesController::class, 'store'])->add(AuthMiddleware::class);
	$app->delete('/categories/{id}', [CategoriesController::class, 'delete'])->add(AuthMiddleware::class);
	// $app->post('/categories/{id}', [CategoriesController::class, 'testing'])->add(AuthMiddleware::class);
	// $app->get('/categories/test', [CategoriesController::class, 'testing']);
};

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
use App\Routing\RouteCollectorProxy;

// $viewRenderer = new ViewRenderer(VIEW_PATH);
// $app = new App($viewRenderer);

// $app->get('/', [TestController::class, 'index']);
// $app->get('/json', [TestController::class, 'json']);

// $app->run();

return function (App $app) {

	$app->get('/', [TestController::class, 'index'])->add(AuthMiddleware::class);
	$app->get('/json', [TestController::class, 'json'])->add(AuthMiddleware::class);



	$app->group('', function (RouteCollectorProxy $guest) {
		$guest->get('/login', [TestController::class, 'loginView']);
		$guest->get('/register', [TestController::class, 'registerView']);
		$guest->post('/login', [TestController::class, 'login']);
		$guest->post('/register', [TestController::class, 'register']);
	}, [GuestMiddleware::class]);

	$app->post('/logout', [TestController::class, 'logout'])->add(AuthMiddleware::class);

	$app->group('/categories', function (RouteCollectorProxy $categories) {
		$categories->get('', [CategoriesController::class, 'index']);
		$categories->post('', [CategoriesController::class, 'store']);
		$categories->delete('/{id}', [CategoriesController::class, 'delete']);
		$categories->get('/{id}', [CategoriesController::class, 'get']);
	}, [AuthMiddleware::class]);
};

$app->get('/test-category', function ($req, $res) {
	return $res->write('Route works!');
});

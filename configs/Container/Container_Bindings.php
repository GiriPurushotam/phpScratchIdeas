<?php

declare(strict_types=1);

use App\Controller\TestController;
use App\ViewRenderer;
use Config\Container\ContainerInterface;
use App\App;
use App\Factory\AppFactory;

$config = require CONFIG_PATH . '/app_config.php';

return [

	App::class => function (ContainerInterface $c) {
		AppFactory::setContainer($c);
		$app = AppFactory::create();

		(require ROUTE_PATH . '/Web.php')($app);
		(require MIDDLEWARE_PATH . '/GlobalMiddleware.php')($app);

		return $app;
	},
	'message' => fn() => 'hello world',

	ViewRenderer::class => fn() => new ViewRenderer(VIEW_PATH, ['cache' => false]),
	'view' => get(ViewRenderer::class),
	TestController::class => fn($c) => new TestController($c->get(ViewRenderer::class)),

	PDO::class => function () use ($config) {
		$db = $config['database'];

		$dsn = sprintf(
			'%s:host=%s;port=%d;dbname=%s;charsert=%s',
			$db['driver'],
			$db['host'],
			$db['port'],
			$db['dbname'],
			$db['charset'] ?? 'utf8mb4',

		);

		$options = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => false,
		];

		return new PDO($dsn, $db['user'], $db['password'], $options);
	}



];

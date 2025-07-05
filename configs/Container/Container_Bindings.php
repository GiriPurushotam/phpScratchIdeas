<?php
declare(strict_types=1);

use App\Controller\TestController;
use App\ViewRenderer;
use Config\Container\ContainerInterface;
use App\App;
use App\Factory\AppFactory;

return[

	App::class => function(ContainerInterface $c) {
		AppFactory::setContainer($c);
		$app = AppFactory::create();

		(require ROUTE_PATH . '/Web.php') ($app);

		return $app;
	},
	'message' => fn() => 'hello world',
	
	ViewRenderer::class => fn () => new ViewRenderer(VIEW_PATH, ['cache' => false]), 'view' => get(ViewRenderer::class),
	TestController::class => fn ($c) => new TestController($c->get(ViewRenderer::class)), 

];

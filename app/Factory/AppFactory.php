<?php

declare(strict_types=1);

namespace App\Factory;

use App\App;
use App\ViewRenderer;
use Config\Container\ContainerInterface;

class AppFactory {
	protected static ?ContainerInterface $container = null;

	public static function setContainer(ContainerInterface $container): void {
		self::$container = $container;
	}

	public static function create(): App {
		if(self::$container === null) {
			throw new \RuntimeException("AppFactory: Container not set");
		}

		$viewRenderer = self::$container->get(ViewRenderer::class);

		$app = new App($viewRenderer);

		return $app;
	}
}
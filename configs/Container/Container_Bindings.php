<?php

declare(strict_types=1);

use App\App;
use App\Auth;
use App\Config;
use App\Session;
use App\ViewRenderer;
use App\Enum\SameSite;
use App\Factory\AppFactory;
use App\Http\ResponseInterface;
use App\Contracts\AuthInterface;
use App\Factory\ResponseFactory;
use App\Controller\TestController;
use App\DataObjects\SessionConfig;
use App\Contracts\SessionInterface;
use App\Services\UserServiceProvider;
use Config\Container\ContainerInterface;
use App\Contracts\ResponseFactoryInterface;
use App\Contracts\UserProviderServiceInterface;
use App\RequestValidator\RequestValidatorFactory;
use App\Contracts\RequestValidatorFactoryInterface;
use Config\Container\DiContainer;

require_once CONFIG_PATH . '/Container/Di_Helpers.php';

$config = require CONFIG_PATH . '/app_config.php';

return [

	ContainerInterface::class => fn(DiContainer $c) => $c,

	App::class => function (ContainerInterface $c) {
		AppFactory::setContainer($c);
		$app = AppFactory::create();

		(require ROUTE_PATH . '/Web.php')($app);
		(require MIDDLEWARE_PATH . '/GlobalMiddleware.php')($app);

		return $app;
	},
	// 'message' => fn() => 'hello world',


	Config::class => create(Config::class)->constructor($config),
	PDO::class => function () use ($config) {
		$db = $config['database'];

		$dsn = sprintf(
			'%s:host=%s;port=%d;dbname=%s;charset=%s',
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
	},

	ViewRenderer::class => fn() => new ViewRenderer(VIEW_PATH, ['cache' => false]),
	'view' => get(ViewRenderer::class),
	TestController::class => fn($c) => new TestController(
		$c->get(ViewRenderer::class),
		$c->get(PDO::class),
		$c->get(AuthInterface::class),
		$c->get(RequestValidatorFactoryInterface::class)

	),

	ResponseFactoryInterface::class => fn($c) => new ResponseFactory(),

	AuthInterface::class => fn(ContainerInterface $c) => $c->get(Auth::class),

	UserProviderServiceInterface::class => fn(ContainerInterface $c) => $c->get(UserServiceProvider::class),

	// SessionInterface::class => fn(ContainerInterface $c) => new Session($c->get(Config::class)->get('session', [])),

	SessionInterface::class => function (ContainerInterface $c) {
		$config = $c->get(Config::class);

		return new Session(
			new SessionConfig(
				$config->get('session.name', ''),
				$config->get('session.flash_name', 'flash'),
				$config->get('session.secure', 'true'),
				$config->get('session.httponly', 'true'),
				SameSite::from($config->get('session.samesite', 'lax')),
			)
		);
	},

	RequestValidatorFactoryInterface::class => fn(ContainerInterface $c) => $c->get(RequestValidatorFactory::class),


];

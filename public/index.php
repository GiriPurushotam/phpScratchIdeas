<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\App;

$container = require_once __DIR__ . '/../bootstrap.php';
// var_dump($container);

$app = $container->get(App::class);

$app->run();

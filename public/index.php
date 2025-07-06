<?php

declare(strict_types=1);

use App\App;

$container = require_once __DIR__ . '/../bootstrap.php';
// var_dump($container);
$app = $container->get(App::class);

$app->run();

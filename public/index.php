<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\App;


$container = require_once __DIR__ . '/../bootstrap.php';
// var_dump($container);
ini_set('display_errors', '1');
error_reporting(E_ALL);
$app = $container->get(App::class);



$app->run();

<?php

declare(strict_types=1);


use Dotenv\Dotenv;
use App\Enum\EnvSelector;


require_once __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/configs/path_constants.php';


$envFile = EnvSelector::select()->value;
$dotenv = Dotenv::createImmutable(__DIR__, $envFile);
$dotenv->load();

if (getenv('APP_DEBUG') === 'true') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
}

$test = $_ENV['APP_ENVIRONMENT'];
var_dump($test);

$test2 = getenv('APP_ENVIRONMENT');
var_dump($test2);


return require CONFIG_PATH . '/container/container.php';

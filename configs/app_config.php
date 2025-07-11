<?php

declare(strict_types=1);

use App\Enum\AppEnvironment;

$appEnv = $_ENV['APP_ENV'] ?? AppEnvironment::DEVELOPMENT->value;

return [
    'app_name'               => $_ENV['APP_NAME'],
    'app_version'            => $_ENV['APP_VERSION'],
    'app_env'                => $appEnv,
    'display_error_details'  => (bool) ($_ENV['APP_DEBUG'] ?? 0),
    'log_error' => true,
    'log_error_details'      => true,

    'database'              => [
        'driver'            => $_ENV['DB_DRIVER'] ?? 'mysql',
        'host'              => $_ENV['DB_HOST'] ?? 'localhost',
        'port'              => $_ENV['DB_PORT'] ?? 3306,
        'dbname'            => $_ENV['DB_NAME'],
        'user'              => $_ENV['DB_USER'],
        'password'          => $_ENV['DB_PASS'],
        'charset'           => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
    ]
];

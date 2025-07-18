<?php

declare(strict_types=1);

use Config\Container\ContainerInterface;

/**
 * @var ContainerInterface $container
 */

$container = require __DIR__ . '/../bootstrap.php';

$pdo = $container->get(PDO::class);

$migrations = require __DIR__ . '/16072025_create_users_table.php';

$migrations($pdo);

echo "Users table created sucessfully";

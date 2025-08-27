<?php

declare(strict_types=1);

use Config\Container\ContainerInterface;

/**
 * @var ContainerInterface $container
 */

$container = require __DIR__ . '/../bootstrap.php';

$pdo = $container->get(PDO::class);

$migrations = require __DIR__ . '/27082025_create_categories_table.php';

$migrations($pdo);

echo "Categories table created sucessfully";

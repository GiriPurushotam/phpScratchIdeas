<?php

declare(strict_types=1);

use Config\Container\ContainerInterface;

/**
 * Load the bootstrap and get PDO instance
 */

/**
 * @var ContainerInterface $container
 */

$container = require __DIR__ . '/bootstrap.php';
$pdo = $container->get(PDO::class);


/**
 * Load all migration files from one migration subfolder
 */

$migrationsFile = glob(__DIR__ . '/migrations/*.php');

if (! $migrationsFile) {
    echo "No migrations found" . PHP_EOL;
    exit;
}


/**
 *      Execute each migration safely
 */



foreach ($migrationsFile as $file) {
    try {
        $migration = require $file;
        if (!is_callable($migration)) {
            throw new \RuntimeException("Migration file" . basename($file) . "does not return a callable");
        }

        $migration($pdo);
        echo basename($file) . "Migration Created Sucessfully";
    } catch (\Throwable $e) {
        echo "Error running migration" . basename($file) . ": " . $e->getMessage() . PHP_EOL;
    }
}

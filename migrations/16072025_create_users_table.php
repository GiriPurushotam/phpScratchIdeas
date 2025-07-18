<?php

declare(strict_types=1);

return function (PDO $pdo) {
    $sql = <<<SQL
    CREATE TABLE IF NOT EXISTS users(
        id INT AUTO_INCREMENT PRIMARY KEY, 
        name VARCHAR(155) NOT NULL, 
        email VARCHAR(155) NOT NULL UNIQUE, 
        password VARCHAR(255) NOT NULL, 
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    SQL;

    // $sql = <<<SQL
    // DROP TABLE IF EXISTS users;
    // SQL;

    $pdo->exec($sql);
};

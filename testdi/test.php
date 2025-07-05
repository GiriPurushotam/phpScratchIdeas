<?php

require __DIR__ . '/Repository.php';
require __DIR__ . '/Database.php';
require __DIR__ . '/Container.php';

$container = new Container();
$container->setClassName(Database::class, function() { return new Database(
host: "localhost",
database: "school",
username: "root",
password: "",
); });
$repo = $container->getClassName(Repository::class);
$result = $repo->getAll();

echo '<pre>';
print_r($result);	
echo '<pre>';



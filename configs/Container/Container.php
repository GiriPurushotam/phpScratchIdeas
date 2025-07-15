<?php

declare(strict_types=1);

use App\Controller\TestController;
use Config\Container\ContainerBuilder;

require_once CONFIG_PATH . '/Container/ContainerHelper.php';
$definitions = require CONFIG_PATH . '/container/container_bindings.php';
$builder = new ContainerBuilder();
$builder->addDefinitions($definitions);
$container = $builder->build();

// testing db connection 

// try {
//     $pdo = $container->get(PDO::class);
//     echo " PDO Conected sucessfully";
// } catch (PDOException $e) {
//     echo "PDO Connection failed" . $e->getMessage();
// }


return $container;

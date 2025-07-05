<?php

declare(strict_types=1);

use App\Controller\TestController;
use Config\Container\ContainerBuilder;

require_once CONFIG_PATH . '/Container/ContainerHelper.php';
$definitions = require CONFIG_PATH . '/container/container_bindings.php';
$builder = new ContainerBuilder();
$builder->addDefinitions($definitions);
return $builder->build();	

// return $container;


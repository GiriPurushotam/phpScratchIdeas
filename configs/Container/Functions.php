<?php

declare(strict_types=1);

namespace Config\Container;

function create(string $class): Definitions
{

    return new Definitions($class);
}

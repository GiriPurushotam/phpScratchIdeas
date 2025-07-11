<?php

declare(strict_types=1);

namespace App\Enum;

enum AppEnvironment: string
{
    case DEVELOPMENT = 'development';
    case PRODUCTION = 'production';

    public function isDevelopment(string $appEnvironment): bool
    {
        return self::tryFrom($appEnvironment) === self::DEVELOPMENT;
    }

    public function isProduction(string $appEnvironment): bool
    {
        return self::tryFrom($appEnvironment) === self::PRODUCTION;
    }
}

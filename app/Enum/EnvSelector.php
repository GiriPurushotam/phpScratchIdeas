<?php

declare(strict_types=1);

namespace App\Enum;

enum EnvSelector: string
{
    case WEB = '.env';
    case CLI = '.env.cli';
    case TESTING = '.env.testing';

    public static function select(): self
    {
        if (php_sapi_name() === 'cli') {
            return self::CLI;
        } else if (defined('PHPUNIT_COMPOSER_INSTALL') || getenv('APP_ENVIRONMENT')  === 'testing') {
            return self::TESTING;
        }

        return self::WEB;
    }
}

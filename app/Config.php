<?php

declare(strict_types=1);


namespace App;

class Config
{


    public function __construct(private readonly array $config) {}


    public function get(string $key, mixed $default = null): mixed
    {

        $path = explode('.', $key);
        $value = $this->config[array_shift($path)] ?? null;

        if ($value === null) {
            return $default;
        }

        foreach ($path as $name) {
            if (! isset($value[$name])) {
                return $default;
            }

            $value = $value[$name];
        }
        return $value;
    }
}

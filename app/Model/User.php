<?php

declare(strict_types=1);

namespace App\Model;

use App\Contracts\UserInterface;

class User implements UserInterface
{

    public function __construct(
        private readonly int $id,
        private readonly string $password,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}

<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\User;

class Category
{

    private int $id;
    private string $name;
    private User $user;

    public function __construct(int $id, string $name, User $user)
    {
        $this->id = $id;
        $this->name = $name;
        $this->user = $user;
    }

    public function getId(): int
    {

        return $this->id;
    }

    public function getName(): string
    {

        return $this->name;
    }

    public function getUserId(): User
    {

        return $this->user;
    }
}

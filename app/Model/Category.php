<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\User;

class Category
{

    private int $id;
    private string $name;
    private int $userId;

    public function __construct(int $id, string $name, int $userId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->userId = $userId;
    }

    public function getId(): int
    {

        return $this->id;
    }

    public function getName(): string
    {

        return $this->name;
    }

    public function getUserId(): int
    {

        return $this->userId;
    }
}

<?php

declare(strict_types=1);

namespace App\Contracts;

interface CategoryInterface
{

    public function getId(): int;

    public function getName(): string;

    public function getUserId(): int;
}

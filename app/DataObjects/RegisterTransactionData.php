<?php

declare(strict_types=1);

namespace App\DataObjects;

class RegisterTransactionData
{
    public function __construct(
        public readonly int $categoryId,
        public readonly string $description,
        public readonly \DateTimeImmutable $date,
        public readonly float $amount
    ) {}
}

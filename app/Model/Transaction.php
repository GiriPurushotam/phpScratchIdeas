<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeImmutable;

class Transaction
{
    private int $id;
    private int $user_id;
    private int $category_id;
    private string $description;
    private DateTimeImmutable $date;
    private float $amount;
    private DateTimeImmutable $created_at;
    private DateTimeImmutable $updated_at;


    public function __construct(
        int $id,
        int $user_id,
        int $category_id,
        string $description,
        DateTimeImmutable $date,
        float $amount,
        DateTimeImmutable $created_at,
        DateTimeImmutable $updated_at

    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->category_id = $category_id;
        $this->description = $description;
        $this->date = $date;
        $this->amount = $amount;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }


    public function getAmount(): float
    {
        return $this->amount;
    }


    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }


    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updated_at;
    }
}

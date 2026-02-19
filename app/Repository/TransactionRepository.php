<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Transaction;
use PDO;

class TransactionRepository
{
    public function __construct(private readonly PDO $pdo) {}


    public function hydrate(array $row): Transaction
    {

        return new Transaction(
            (int) $row['id'],
            (int) $row['user_id'],
            (int) $row['category_id'],
            $row['description'],
            new \DateTimeImmutable($row['date']),
            (float) $row['amount'],
            new \DateTimeImmutable($row['created_at']),
            new \DateTimeImmutable($row['updated_at'])

        );
    }



    public function create(
        int $userId,
        int $categoryId,
        string $description,
        \DateTimeImmutable $date,
        float $amount
    ): Transaction {
        $stmt = $this->pdo->prepare("INSERT INTO transactions
        (user_id, category_id, description, date, amount, created_at, updated_at) VALUES (:user_id, :category_id, :description, :date, :amount, NOW(), NOW()) 
        ");

        $stmt->execute([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'description' => $description,
            'date' => $date->format('Y-m-d'),
            'amount' => $amount,
        ]);

        $id = (int) $this->pdo->lastInsertId();
        return $this->find($id);
    }


    public function find(int $id): ?Transaction
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transactions WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $this->hydrate($row);
        }

        return null;
    }
}

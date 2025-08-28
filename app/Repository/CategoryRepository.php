<?php


declare(strict_types=1);

namespace App\Repository;

use PDO;

class CategoryRepository
{

    public function __construct(private readonly PDO $pdo) {}

    public function create(string $name, int $userId): void
    {

        $stmt = $this->pdo->prepare("INSERT INTO categories (name, user_id) VALUES (:name, :user_id)");

        $stmt->execute([
            'name' => $name,
            'user_id' => $userId
        ]);
    }

    public function findByUser(int $userId): array
    {

        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE user_id = :user_id ORDER BY created_at DESC");

        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function delete(): void {}
}

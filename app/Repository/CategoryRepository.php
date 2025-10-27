<?php


declare(strict_types=1);

namespace App\Repository;

use App\Model\Category;
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



    public function delete(int $id): void
    {

        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id =:id");
        $stmt->execute(['id' => $id]);
    }

    public function find(int $id): ?Category
    {

        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id =:id");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Category((int) $row['id'], $row['name'], (int)$row['user_id']);
        }

        return null;
    }

    public function edit(int $id, string $name): void
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET name = :name, updated_at = NOW() WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'name' => $name
        ]);
    }
}

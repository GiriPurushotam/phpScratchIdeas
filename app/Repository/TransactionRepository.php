<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Transaction;
use App\Support\Paginator;
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


    public function paginateByUser(
        int $userId,
        int $start,
        int $length,
        string $orderBy,
        string $orderDir,
        string $search
    ): Paginator {

        $allowedColumns = [
            'description' => 't.description',
            'amount' => 't.amount',
            'date' => 't.date',
            'category' => 'c.name',
            'createdAt' => 't.created_at',
            'updated_at' => 't.updated_at',
        ];

        $orderBy = $allowedColumns[$orderBy] ?? 'created_at';
        $orderDir = strtolower($orderDir) === 'asc' ? 'ASC' : 'DESC';

        $where = "WHERE t.user_id =:user_id";
        $params = ['user_id' => $userId];


        if (! empty($search)) {
            $escapedSearch = addcslashes($search, '%_');
            $where .= " AND t.description LIKE :search OR c.name LIKE :search";
            $params['search'] = "%$escapedSearch%";
        }

        $totalStmt = $this->pdo->prepare("SELECT COUNT(*) FROM transactions WHERE user_id =:user_id");

        $totalStmt->execute(['user_id' => $userId]);
        $total = (int) $totalStmt->fetchColumn();

        // filtered record query 

        $filterStmt = $this->pdo->prepare("SELECT COUNT(*) FROM transactions t JOIN categories c ON c.id = t.category_id $where");
        $filterStmt->execute($params);
        $filtered = $filterStmt->fetchColumn();


        $sql = "SELECT t.id, t.description, t.amount, t.date, c.name AS category, t.created_at, t.updated_at FROM transactions t JOIN categories c ON c.id = t.category_id $where ORDER BY $orderBy $orderDir LIMIT :length OFFSET :start ";

        $dataStmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $dataStmt->bindValue(":$key", $value);
        }

        $dataStmt->bindValue(':length', $length, \PDO::PARAM_INT);
        $dataStmt->bindValue(':start', $start, \PDO::PARAM_INT);

        $dataStmt->execute();
        $items = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

        return new Paginator($items, $total, $start, $length, $filtered);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM transactions WHERE id =:id");
        $stmt->execute(['id' => $id]);
    }
}

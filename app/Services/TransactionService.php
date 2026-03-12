<?php

declare(strict_types=1);

namespace App\Services;

use App\DataObjects\RegisterTransactionData;
use App\Model\Transaction;
use App\Model\User;
use App\Repository\TransactionRepository;
use App\Support\Paginator;

class TransactionService
{
    public function __construct(private readonly TransactionRepository $transactionRepository) {}


    public function create(RegisterTransactionData $data, User $user): Transaction
    {
        return $this->transactionRepository->create(
            userId: $user->getId(),
            categoryId: $data->categoryId,
            description: $data->description,
            date: $data->date,
            amount: $data->amount
        );
    }


    public function getPaginatedTransactions(
        int $userId,
        int $start,
        int $length,
        string $orderBy,
        string $orderDir,
        string $search
    ): Paginator {
        return $this->transactionRepository->paginateByUser(
            $userId,
            $start,
            $length,
            $orderBy,
            $orderDir,
            $search
        );
    }

    public function getById(int $id): ?Transaction
    {
        return $this->transactionRepository->find($id);
    }


    public function delete(int $id): void
    {
        $this->transactionRepository->delete($id);
    }
}

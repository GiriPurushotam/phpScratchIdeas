<?php

declare(strict_types=1);

namespace App\Services;

use App\DataObjects\RegisterTransactionData;
use App\Model\Transaction;
use App\Model\User;
use App\Repository\TransactionRepository;

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
}

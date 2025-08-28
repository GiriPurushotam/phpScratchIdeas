<?php

declare(strict_types=1);

namespace App\Services;

use App\Model\Category;
use App\Model\User;
use App\Repository\CategoryRepository;
use PDO;

class CategoryService
{
    public function __construct(private readonly CategoryRepository $categoryRepository) {}

    public function create(string $name, User $user): void
    {

        $this->categoryRepository->create($name, $user->getId());
    }

    public function getAll(int $userId): array
    {

        return $this->categoryRepository->findByUser($userId);
    }

    public function delete(int $id): void
    {

        $this->categoryRepository->delete();
    }
}

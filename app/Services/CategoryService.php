<?php

declare(strict_types=1);

namespace App\Services;

use App\Model\Category;
use App\Model\User;
use App\Repository\CategoryRepository;
use App\Support\Paginator;
use PDO;

class CategoryService
{
    public function __construct(private readonly CategoryRepository $categoryRepository) {}

    public function create(string $name, User $user): void
    {

        $this->categoryRepository->create($name, $user->getId());
    }

    public function getPaginatedCategories(int $userId, int $start, int $length, string $orderBy, string $OrderDir, string $search): Paginator
    {

        return $this->categoryRepository->paginateByUser($userId, $start, $length, $orderBy, $OrderDir, $search);
    }

    public function delete(int $id): void
    {

        $this->categoryRepository->delete($id);
    }

    public function getById(int $id): ?Category
    {

        return $this->categoryRepository->find($id);
    }

    public function update(Category $category, string $name): void
    {
        $this->categoryRepository->edit($category->getId(), $name);
    }
}

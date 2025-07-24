<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\UserInterface;
use App\Contracts\UserProviderServiceInterface;
use App\Model\User;
use PDO;

class UserServiceProvider implements UserProviderServiceInterface
{

    public function __construct(private readonly PDO $pdo) {}

    public function getById(int $id): ?UserInterface
    {

        $stmt = $this->pdo->prepare("SELECT id, password FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (! $data) {
            return null;
        }

        return new User((int) $data['id'], $data['password']);
    }

    public function getByCredentials(array $credentials): ?UserInterface
    {

        $stmt = $this->pdo->prepare("SELECT id, password FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $credentials['email']]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (! $data) {
            return null;
        }

        return new User((int) $data['id'], $data['password']);
    }
}

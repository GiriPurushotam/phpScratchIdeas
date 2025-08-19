<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Contracts\UserInterface;
use App\DataObjects\RegisterUserData;

interface AuthInterface
{

    public function user(): ?UserInterface;

    public function attemptLogin(array $credential): bool;

    public function checkCredentials(UserInterface $user, array $credential): bool;

    public function register(RegisterUserData $data): UserInterface;

    public function logout(): void;
}

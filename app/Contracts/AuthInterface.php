<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Contracts\UserInterface;

interface AuthInterface
{

    public function user(): ?UserInterface;

    public function attempLogin(array $credential): bool;

    public function checkCredentials(UserInterface $user, array $credential): bool;

    public function logout(): void;
}

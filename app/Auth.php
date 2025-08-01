<?php

declare(strict_types=1);

namespace App;

use App\Contracts\AuthInterface;
use App\Contracts\SessionInterface;
use App\Contracts\UserInterface;
use App\Contracts\UserProviderServiceInterface;

class Auth implements AuthInterface
{
    private ?UserInterface $user = null;

    public function __construct(private readonly UserProviderServiceInterface $userProvider, private readonly SessionInterface $session) {}

    public function attemptLogin(array $credential): bool
    {
        $user = $this->userProvider->getByCredentials($credential);
        if (! $user || !$this->checkCredentials($user, $credential)) {
            return false;
        }

        $this->session->reGenerate();


        $this->session->put('user', [
            'id' => $user->getId(),
            'name' => $user->getName(),
        ]);
        // $_SESSION['user'] =  [
        //     'id' => $user->getId(),
        //     'name' => $user->getName(),
        // ];
        $this->user = $user;
        return true;
    }

    public function checkCredentials(UserInterface $user, array $credential): bool
    {
        $hashed = $user->getPassword();
        $plain = $credential['password'];

        if (str_starts_with($hashed, '$2y$')) {
            return password_verify($plain, $hashed);
        }

        return $plain === $hashed;

        // return password_verify($credential['password'], $user->getPassword());
    }

    public function user(): ?UserInterface
    {

        if ($this->user !== null) {
            return $this->user;
        }

        $userId = $this->session->get('user');
        // $userId = $_SESSION['user'] ?? null;

        if (!is_array($userId) || !isset($userId['id'])) {
            return null;
        }

        $user = $this->userProvider->getById((int) $userId['id']);

        if (!$user) {
            return null;
        }

        $this->user = $user;

        return $this->user;
    }

    public function logout(): void
    {
        $this->session->forget('user');
        $this->session->reGenerate();
        $this->user = null;
    }
}

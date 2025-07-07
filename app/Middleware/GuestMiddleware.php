<?php

declare(strict_types=1);

namespace App\Middleware;

class GuestMiddleware
{
    public function handle(callable $next): void
    {
        if (!empty($_SESSION['user'])) {
            header('Location:' . BASE_PATH . '/login');
            exit();
        }

        $next();
    }
}

<?php

declare(strict_types=1);

namespace App\Middleware;

class AuthMiddleware
{
	public function handle(callable $next): void
	{
		if (!isset($_SESSION['user'])) {
			http_response_code(403);
			echo "Forbidden: You must be logged in";
			return;
		}

		$next;
	}
}

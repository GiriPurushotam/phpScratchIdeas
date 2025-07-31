<?php

declare(strict_types=1);

namespace App;

use App\Contracts\SessionInterface;
use App\Exceptions\SessionException;

class Session implements SessionInterface
{

    public function start(): void
    {
        if (session_start() === PHP_SESSION_ACTIVE) {
            throw new SessionException("Session already started");
        }

        if (headers_sent($fileName, $line)) {
            throw new SessionException("Header has been already sent in [$fileName] on line [$line] ");
        }

        session_set_cookie_params(['secure' => true, 'httponly' => true, 'samesite' => 'lax']);

        session_start();
    }

    public function save(): void
    {

        session_write_close();
    }
}

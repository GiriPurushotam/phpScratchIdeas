<?php

declare(strict_types=1);
const CONFIG_PATH = __DIR__ . '/../configs/';
const APP_PATH = __DIR__ . '/../app/';
const VIEW_PATH = __DIR__ . '/../views/';
const ROUTE_PATH = __DIR__ . '/../routes/';
const MIDDLEWARE_PATH = __DIR__ . '/../app/Middleware/';
define('BASE_PATH', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));

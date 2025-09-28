<?php

declare(strict_types=1);

// phpinfo();



require __DIR__ . '/../vendor/autoload.php';

use App\App;
use App\Exceptions\FrameworkException;
use App\Http\Response;

$container = require_once __DIR__ . '/../bootstrap.php';

$app = $container->get(App::class);

set_exception_handler(function (Throwable $e) {
    $isFrameworkError = $e instanceof FrameworkException;

    echo "<h1>Uncaught Exception</h1>";
    echo "<p><strong>Error:</strong> " . ($isFrameworkError ? 'framework-error' : 'unhandled error') . "</p>";
    echo "<p><strong>Message:</strong> {$e->getMessage()}</p>";
    echo "<p><strong>File:</strong> {$e->getFile()}</p>";
    echo "<p><strong>Line:</strong> {$e->getLine()}</p>";
    echo "<pre><strong>Trace:</strong>\n{$e->getTraceAsString()}</pre>";
    http_response_code(500);
    // die('Reached exception handler');
});


try {
    $app->run();
} catch (Throwable $e) {
    echo "Error" . $e->getMessage();
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

<?php

declare(strict_types=1);


require __DIR__ . '/../vendor/autoload.php';

use App\App;
use App\Exceptions\FrameworkException;
use App\Http\Response;

$container = require_once __DIR__ . '/../bootstrap.php';
// var_dump($container);
// ini_set('display_errors', '1');
// error_reporting(E_ALL);
$app = $container->get(App::class);

set_exception_handler(function (Throwable $e) {
    $isFrameworkError = $e instanceof FrameworkException;

    $data = [
        'error' => $isFrameworkError ? 'framework-error' : 'unhandled error',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ];

    echo "<h1>Uncaught Exception</h1>";
    echo "<p><strong>Error:</strong> . $isFrameworkError ?  . 'framework-error' . </p>";
    echo "<p><strong>Message:</strong> {$e->getMessage()}</p>";
    echo "<p><strong>File:</strong> {$e->getFile()}</p>";
    echo "<p><strong>Line:</strong> {$e->getLine()}</p>";
    echo "<pre><strong>Trace:</strong>\n{$e->getTraceAsString()}</pre>";
    http_response_code(500);
});

$app->run();

// try {
//     $app->run();
// } catch (FrameworkException $e) {

//     $response = new Response();
//     $response->withJson([
//         'error' => 'Framework Error',
//         'message' => $e->getMessage(),
//         'file' => $e->getFile(),
//         'line' => $e->getLine(),
//     ], 500)->send();
// } catch (Throwable $e) {
//     $response = new Response();
//     $response->withJson([
//         'error' => 'Unhandled Error',
//         'message' => $e->getMessage(),
//         'file' => $e->getFile(),
//         'line' => $e->getLine(),
//     ], 500)->send();
// }

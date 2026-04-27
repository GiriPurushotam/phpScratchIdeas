<?php

declare(strict_types=1);

namespace App\Controller;

use League\Flysystem\Filesystem;
use App\Http\ServerRequestInterface as Request;
use App\Http\ResponseInterface as Response;

class ReceiptController
{
    public function __construct(private readonly Filesystem $filesystem) {}


    public function store(Request $request, Response $response, array $args): Response
    {
        $file = $request->getUploadedFiles()['receipt'];

        return $response;
    }
}

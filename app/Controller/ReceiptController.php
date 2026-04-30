<?php

declare(strict_types=1);

namespace App\Controller;

use League\Flysystem\Filesystem;
use App\Http\ServerRequestInterface as Request;
use App\Http\ResponseInterface as Response;
use App\Http\UploadedFile;

class ReceiptController
{
    public function __construct(private readonly Filesystem $filesystem) {}


    public function store(Request $request, Response $response, array $args): Response
    {
        /** @var UploadedFile $file */
        $file = $request->getUploadedFiles()['receipt'];
        $fileName = $file->getClientFilename();
        $this->filesystem->write('receipts/' . $fileName, $file->getStream()->getContents());

        return $response;
    }
}

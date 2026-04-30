<?php

declare(strict_types=1);

namespace App\Http;

interface UploadedFileInterface
{
    public function getClientFilename(): ?string;
    public function getClientMediaType(): ?string;
    public function getError(): int;
    public function getSize(): int;
    public function getStream(): StreamInterface;
    public function moveTo(string $targetPath): void;
}

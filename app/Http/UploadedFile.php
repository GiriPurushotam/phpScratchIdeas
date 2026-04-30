<?php

declare(strict_types=1);

namespace App\Http;

use Override;

class UploadedFile implements UploadedFileInterface
{
    private string $clientFileName;
    private string $clientMediaType;
    private int $error;
    private int $size;
    private string $tmpName;

    public function __construct(array $file)
    {
        $this->clientFileName = $file['name'];
        $this->clientMediaType = $file['type'];
        $this->error = $file['error'];
        $this->size = $file['size'];
        $this->tmpName = $file['tmp_name'];
    }


    #[Override]
    public function getClientFilename(): ?string
    {
        return $this->clientFileName;
    }

    #[Override]
    public function getClientMediaType(): ?string
    {
        return $this->clientMediaType;
    }

    #[Override]
    public function getError(): int
    {
        return $this->error;
    }

    #[Override]
    public function getSize(): int
    {
        return $this->size;
    }
    #[Override]
    public function getStream(): StreamInterface
    {
        return new FileStream($this->tmpName);
    }

    #[Override]
    public function moveTo(string $targetPath): void
    {
        move_uploaded_file($this->tmpName, $targetPath);
    }
}

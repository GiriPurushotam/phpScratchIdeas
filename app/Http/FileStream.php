<?php

declare(strict_types=1);

namespace App\Http;

use Override;

class FileStream implements StreamInterface
{
    private $resource;

    public function __construct(string $tmpPath)
    {
        $this->resource = fopen($tmpPath, 'r');
    }

    #[Override]
    public function  __toString(): string
    {
        $this->rewind();
        return $this->getContents();
    }

    public function  close(): void
    {
        fclose($this->resource);
    }

    #[Override]
    public function detach()
    {
        return $this->resource;
    }

    #[Override]
    public function getSize(): int
    {
        return fstat($this->resource)['size'] ?? 0;
    }

    #[Override]
    public function tell(): int
    {
        return ftell($this->resource);
    }

    #[Override]
    public function eof(): bool
    {
        return feof($this->resource);
    }

    #[Override]
    public function isSeekable(): bool
    {
        return true;
    }

    #[Override]
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        fseek($this->resource, $offset, $whence);
    }

    #[Override]
    public function rewind(): void
    {
        rewind($this->resource);
    }

    #[Override]
    public function isWritable(): bool
    {
        return false;
    }

    #[Override]
    public function write(string $string): bool
    {
        return false;
    }

    #[Override]
    public function isReadable(): bool
    {
        return true;
    }

    #[Override]
    public function read(int $length): string
    {
        return fread($this->resource, $length);
    }

    #[Override]
    public function getContents(): string
    {
        return stream_get_contents($this->resource);
    }

    #[Override]
    public function getMetadata(?string $key = null)
    {
        $meta = stream_get_meta_data($this->resource);
        return $key ? ($meta[$key] ?? null) : $meta;
    }
}

<?php

declare(strict_types=1);

namespace App\Http;

class PhpInputStream implements StreamInterface
{

    private $stream;

    public function __construct()
    {
        $this->stream = fopen('php://input', 'r+');
    }

    public function _toString(): string
    {

        return stream_get_contents($this->stream);
    }

    public function close(): void
    {
        fclose($this->stream);
    }

    public function detach()
    {

        $result = $this->stream;
        $this->stream = null;
        return $result;
    }

    public function getSize(): int
    {
        $stats = fstat($this->stream);
        return $stats['size'] ?? null;
    }

    public function tell(): int
    {
        return ftell($this->stream);
    }

    public function eof(): bool
    {
        return feof($this->stream);
    }

    public function isSeekable(): bool
    {
        $meta = stream_get_meta_data($this->stream);
        return $meta['seekable'];
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        fseek($this->stream, $offset, $whence);
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        $meta = stream_get_meta_data($this->stream);
        return str_contains($meta['mode'], '+') || str_contains($meta['mode'], 'w') || str_contains($meta['mode'], 'a');
    }

    public function write(string $string): bool
    {
        return fwrite($this->stream, $string);
    }

    public function isReadable(): bool
    {
        $meta = stream_get_meta_data($this->stream);
        return str_contains($meta['mode'], 'r') || str_contains($meta['mode'], '+');
    }

    public function read(int $length): string
    {
        return fread($this->stream, $length);
    }

    public function getContents(): string
    {
        return stream_get_contents($this->stream);
    }

    public function getMetadata(?string $key = null)
    {
        $meta = stream_get_meta_data($this->stream);

        return $key ? ($meta[$key] ?? null) : $meta;
    }
}

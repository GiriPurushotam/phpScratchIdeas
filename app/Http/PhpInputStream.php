<?php

declare(strict_types=1);

namespace App\Http;

class PhpInputStream implements StreamInterface
{


    private string $contents;
    private int $position = 0;

    public function __construct()
    {
        $this->contents = file_get_contents('php://input');
        // rewind($this->stream);
    }

    public function __toString(): string
    {
        $this->rewind();

        return $this->contents;
    }

    public function close(): void
    {
        // nothing to close cause we read into memory
    }

    public function detach()
    {

        $this->contents = '';
        $this->position = 0;
        return null;
    }

    public function getSize(): int
    {
        return strlen($this->contents);
    }

    public function tell(): int
    {
        return $this->position;
    }

    public function eof(): bool
    {
        return $this->position >= strlen($this->contents);
    }

    public function isSeekable(): bool
    {
        return true;
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {

        $length = strlen($this->contents);
        switch ($whence) {
            case SEEK_SET:
                $this->position = max(0, min($offset, $length));
                break;
            case SEEK_CUR:
                $this->position = max(0, min($this->position + $offset, $length));
                break;
            case SEEK_END;
                $this->position = max(0, min($length + $offset, $length));
                break;
        }
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function write(string $string): bool
    {

        return false;
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function read(int $length): string
    {
        if ($this->eof()) {
            return '';
        }
        $chunk = substr($this->contents, $this->position, $length);
        $this->position += strlen($chunk);
        return $chunk;
    }

    public function getContents(): string
    {
        $this->rewind();
        return $this->contents;
    }

    public function getMetadata(?string $key = null)
    {
        return null;
    }
}

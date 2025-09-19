<?php

declare(strict_types=1);

namespace App\Formatter;

class Stream
{

    private string $content;


    public function __construct(string $content)
    {
        $this->content = &$content;
    }

    public function write(string $data): void
    {
        $this->content .= $data;
    }

    public function __toString(): string
    {
        return $this->content;
    }
}

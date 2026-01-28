<?php


declare(strict_types=1);

namespace App\Support;

use IteratorAggregate;
use Traversable;

class Paginator implements \IteratorAggregate
{
    private array $items;
    private int $total;
    private int $start;
    private int $length;
    private int $filtered;

    public function __construct(array $items, int $total, int $start, int $length, int $filtered)
    {
        $this->items = $items;
        $this->total = $total;
        $this->start = $start;
        $this->length = $length;
        $this->filtered = $filtered;

    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->items);
    }

    public function total(): int
    {
        return $this->total;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function filtered(): int {
        return $this->filtered;
    }
}

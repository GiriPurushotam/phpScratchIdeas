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

    public function __construct(array $items, int $total, int $start, int $length)
    {
        $this->items = $items;
        $this->total = $total;
        $this->start = $start;
        $this->length = $length;
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
}

<?php

namespace App\Model;

class BookListResponse
{
    /**
     * @var BookListItem[]
     */
    private array $items;

    /**
     * @param BookListItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }
}

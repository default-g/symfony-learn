<?php

namespace App\Model;

class BookCategoryListResponse
{
    /**
     * @var BookCategory[]
     */
    private array $items;

    /**
     * @param BookCategory[] $items
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

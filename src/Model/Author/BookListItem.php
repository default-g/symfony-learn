<?php

namespace App\Model\Author;

class BookListItem
{
    private int $id;
    private string $title;

    private string $slug;

    private ?string $image;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): BookListItem
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): BookListItem
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): BookListItem
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): BookListItem
    {
        $this->image = $image;

        return $this;
    }

}

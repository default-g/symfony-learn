<?php

namespace App\Model\Author;

use App\Entity\BookFormat;
use App\Model\BookCategory;

class BookDetails
{
    private int $id;

    private string $title;

    private string $slug;

    private ?string $image;

    /** @var string[] */
    private ?array $authors;

    private ?string $isbn;

    private ?string $description;

    private ?int $publicationDate;

    /** @var BookCategory[] */
    private array $categories = [];

    /** @var BookFormat[] */
    private array $bookFormats = [];


    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): BookDetails
    {
        $this->id = $id;
        return $this;
    }


    public function getTitle(): string
    {
        return $this->title;
    }


    public function setTitle(string $title): BookDetails
    {
        $this->title = $title;
        return $this;
    }


    public function getSlug(): string
    {
        return $this->slug;
    }


    public function setSlug(string $slug): BookDetails
    {
        $this->slug = $slug;
        return $this;
    }


    public function getImage(): ?string
    {
        return $this->image;
    }


    public function setImage(?string $image): BookDetails
    {
        $this->image = $image;
        return $this;
    }


    public function getAuthors(): ?array
    {
        return $this->authors;
    }


    public function setAuthors(?array $authors): BookDetails
    {
        $this->authors = $authors;
        return $this;
    }


    public function getIsbn(): ?string
    {
        return $this->isbn;
    }


    public function setIsbn(?string $isbn): BookDetails
    {
        $this->isbn = $isbn;
        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }


    public function setDescription(?string $description): BookDetails
    {
        $this->description = $description;
        return $this;
    }


    public function getPublicationDate(): ?int
    {
        return $this->publicationDate;
    }


    public function setPublicationDate(?int $publicationDate): BookDetails
    {
        $this->publicationDate = $publicationDate;
        return $this;
    }


    public function getCategories(): array
    {
        return $this->categories;
    }


    public function setCategories(array $categories): BookDetails
    {
        $this->categories = $categories;
        return $this;
    }


    public function getBookFormats(): array
    {
        return $this->bookFormats;
    }


    public function setBookFormats(array $bookFormats): BookDetails
    {
        $this->bookFormats = $bookFormats;
        return $this;
    }



}

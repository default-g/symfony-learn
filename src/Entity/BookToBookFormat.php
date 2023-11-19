<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookToBookFormatRepository::class)]
class BookToBookFormat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private float $price;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int$discountPercent;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'formats')]
    private Book $book;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: BookFormat::class, fetch: 'EAGER')]
    private BookFormat $bookFormat;


    public function getPrice(): float
    {
        return $this->price;
    }


    public function setPrice(float $price): BookToBookFormat
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDiscountPercent(): ?int
    {
        return $this->discountPercent;
    }


    public function setDiscountPercent(?int $discountPercent): BookToBookFormat
    {
        $this->discountPercent = $discountPercent;
        return $this;
    }


    public function getBook(): Book
    {
        return $this->book;
    }


    public function setBook(Book $book): BookToBookFormat
    {
        $this->book = $book;
        return $this;
    }


    public function getBookFormat(): BookFormat
    {
        return $this->bookFormat;
    }


    public function setBookFormat(BookFormat $bookFormat): BookToBookFormat
    {
        $this->bookFormat = $bookFormat;
        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }
}

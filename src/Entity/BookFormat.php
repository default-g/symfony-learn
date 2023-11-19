<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookFormatRepository::class)]
class BookFormat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private float $price;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getTitle(): string
    {
        return $this->title;
    }


    public function setTitle(string $title): BookFormat
    {
        $this->title = $title;
        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }


    public function setDescription(?string $description): BookFormat
    {
        $this->description = $description;
        return $this;
    }


    public function getComment(): ?string
    {
        return $this->comment;
    }


    public function setComment(?string $comment): BookFormat
    {
        $this->comment = $comment;
        return $this;
    }


    public function getPrice(): float
    {
        return $this->price;
    }


    public function setPrice(float $price): BookFormat
    {
        $this->price = $price;
        return $this;
    }



}

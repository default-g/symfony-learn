<?php

namespace App\Model\Author;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class BookFormatOptions
{
    #[NotBlank]
    private int $id;

    #[NotBlank]
    #[Positive]
    private float $price;

    private ?int $discountPercent;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): BookFormatOptions
    {
        $this->id = $id;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): BookFormatOptions
    {
        $this->price = $price;
        return $this;
    }

    public function getDiscountPercent(): ?int
    {
        return $this->discountPercent;
    }

    public function setDiscountPercent(?int $discountPercent): BookFormatOptions
    {
        $this->discountPercent = $discountPercent;
        return $this;
    }



}

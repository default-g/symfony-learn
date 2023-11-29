<?php

namespace App\Mapper;

use App\Entity\Book;
use App\Model\BookDetails;
use App\Model\BookListItem;
use App\Model\RecommendedBook;
use Symfony\Bundle\MakerBundle\Str;

class BookMapper
{
    public static function map(Book $book, BookDetails|BookListItem $model): BookListItem|BookDetails
    {
        return $model
            ->setId($book->getId())
            ->setTitle($book->getTitle())
            ->setSlug($book->getSlug())
            ->setImage($book->getImage())
            ->setMeap($book->isMeap())
            ->setAuthors($book->getAuthors())
            ->setPublicationDate($book->getPublicationDate()->getTimestamp());
    }


    public static function mapRecommended(Book $book): RecommendedBook
    {
        $description = $book->getDescription();
        $description = substr($description, 0, 150);

        return (new RecommendedBook())
            ->setId($book->getId())
            ->setImage($book->getImage())
            ->setSlug($book->getSlug())
            ->setTitle($book->getTitle())
            ->setShortDescription($description);


    }

}

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
}

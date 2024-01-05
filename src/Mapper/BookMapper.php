<?php

namespace App\Mapper;

use App\Entity\Book;
use App\Entity\BookToBookFormat;
use App\Model\BookCategory as BookCategoryModel;
use App\Model\BookDetails;
use App\Model\BookFormat;
use App\Model\BookListItem;
use App\Model\Author\BookDetails as AuthorBookDetails;

class BookMapper
{
    // TODO: use interfaces for models?
    public static function map(Book $book, BookDetails|BookListItem|AuthorBookDetails $model): BookListItem|BookDetails|AuthorBookDetails
    {
        $publicationDate = $book->getPublicationDate();
        if (null !== $publicationDate) {
            $publicationDate = $publicationDate->getTimestamp();
        }

        return $model
            ->setId($book->getId())
            ->setTitle($book->getTitle())
            ->setSlug($book->getSlug())
            ->setImage($book->getImage())
            ->setMeap($book->isMeap())
            ->setAuthors($book->getAuthors())
            ->setPublicationDate($publicationDate);
    }


    /**
     * @return BookCategoryModel[]
     */
    public static function mapCategories(Book $book): array
    {
        return $book->getCategories()
            ->map(fn(\App\Entity\BookCategory $bookCategory) => (new BookCategoryModel(
                    $bookCategory->getId(),
                    $bookCategory->getTitle(),
                    $bookCategory->getSlug()
                ))
            )->toArray();
    }


    /**
     * @return BookFormat[]
     */
    public static function mapFormats(Book $book): array
    {
        return $book->getFormats()->map(fn (BookToBookFormat $bookFormat) => (new BookFormat())
            ->setId($bookFormat->getBookFormat()->getId())
            ->setTitle($bookFormat->getBookFormat()->getTitle())
            ->setPrice($bookFormat->getBookFormat()->getPrice())
            ->setDescription($bookFormat->getBookFormat()->getDescription())
            ->setDiscountPercent($bookFormat->getDiscountPercent())
            ->setPrice($bookFormat->getPrice())
        )->toArray();
    }
}

<?php

namespace App\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookToBookFormat;
use App\Exception\BookCategoryNotFoundException;
use App\Mapper\BookMapper;
use App\Model\BookCategory as BookCategoryModel;
use App\Model\BookDetails;
use App\Model\BookFormat;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\Collection;

class BookService
{
    public function __construct(
        private readonly BookRepository           $bookRepository,
        private readonly BookCategoryRepository   $bookCategoryRepository,
        private readonly RatingService            $ratingService,
    ) {}

    public function getBooksByCategory(int $categoryId): BookListResponse
    {
        if (!$this->bookCategoryRepository->existsById($categoryId)) {
            throw new BookCategoryNotFoundException();
        }

        return new BookListResponse(array_map(
            fn(Book $book) => BookMapper::map($book, new BookListItem()),
            $this->bookRepository->findBooksByCategory($categoryId)
        ));
    }


    public function getBookById(int $bookId): BookDetails
    {
        $book = $this->bookRepository->getById($bookId);

        $formats = $this->mapFormats($book->getFormats());

        $categories = $book->getCategories()
            ->map(
                fn(BookCategory $bookCategory) => (new BookCategoryModel(
                    $bookCategory->getId(), $bookCategory->getTitle(), $bookCategory->getSlug()
                ))
            );

        $rating = $this->ratingService->calculateReviewRatingFromBook($bookId);

        return BookMapper::map($book, new BookDetails())
            ->setRating($rating->getRating())
            ->setReviews($rating->getTotal())
            ->setFormats($formats)
            ->setCategories($categories->toArray());
    }


    /**
     * @param Collection $formats
     * @return BookFormat[]
     */
    private function mapFormats(Collection $formats): array
    {
        return $formats->map(fn (BookToBookFormat $bookFormat) => (new BookFormat())
                ->setId($bookFormat->getBookFormat()->getId())
                ->setTitle($bookFormat->getBookFormat()->getTitle())
                ->setPrice($bookFormat->getBookFormat()->getPrice())
                ->setDescription($bookFormat->getBookFormat()->getDescription())
                ->setDiscountPercent($bookFormat->getDiscountPercent())
                ->setPrice($bookFormat->getPrice())
        )->toArray();
    }
}

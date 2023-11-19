<?php

namespace App\Service;

use App\Entity\Review;
use App\Model\Review as ReviewModel;
use App\Model\ReviewPage;
use App\Repository\ReviewRepository;

class ReviewService
{
    private const PAGE_LIMIT = 5;

    public function __construct(private readonly ReviewRepository $reviewRepository)
    {

    }


    public function getReviewsByBookId(int $id, int $page): ReviewPage
    {
        $offset = max($page - 1, 0) * self::PAGE_LIMIT;
        $paginator = $this->reviewRepository->getPageByBookId($id, $offset, self::PAGE_LIMIT);
        $rating = $this->reviewRepository->getBookTotalRatingSum($id);
        $total = count($paginator);

        return (new ReviewPage())
            ->setRating($total > 0 ? $rating / $total : 0)
            ->setTotal($total)
            ->setPage($page)
            ->setPerPage(self::PAGE_LIMIT)
            ->setPages(ceil($total / self::PAGE_LIMIT))
            ->setItems(array_map([$this, 'map'], $paginator->getIterator()->getArrayCopy()));
    }


    private function map(Review $review): ReviewModel
    {
        return (new ReviewModel())
            ->setId($review->getId())
            ->setRating($review->getRating())
            ->setAuthor($review->getAuthor())
            ->setContent($review->getContent())
            ->setCreatedAt($review->getCreatedAt()->getTimestamp());
    }

}

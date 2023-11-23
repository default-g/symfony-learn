<?php

namespace App\Service;

use App\Repository\ReviewRepository;

class RatingService
{
    public function __construct(private ReviewRepository $reviewRepository)
    {
    }


    public function calculateReviewRatingFromBook(int $id, int $total): float
    {
        return $total > 0 ? $this->reviewRepository->getBookTotalRatingSum($id) / $total : 0;
    }

}

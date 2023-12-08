<?php

namespace App\Service;

use App\Mapper\BookMapper;
use App\Repository\ReviewRepository;
use App\Service\Recommendation\Model\RecommendationItem;

class RatingService
{
    public function __construct(private ReviewRepository $reviewRepository)
    {
    }


    public function calculateReviewRatingFromBook(int $id): Rating
    {
        $total = $this->reviewRepository->countByBookId($id);
        $rating = $total > 0 ? $this->reviewRepository->getBookTotalRatingSum($id) / $total : 0;

        return new Rating($total, $rating);
    }

}

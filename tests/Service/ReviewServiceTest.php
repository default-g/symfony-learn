<?php

namespace App\Tests\Service;

use App\Entity\Review;
use App\Model\ReviewPage;
use App\Repository\ReviewRepository;
use App\Service\RatingService;
use App\Service\ReviewService;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ReviewServiceTest extends AbstractTestCase
{
    private ReviewRepository $reviewRepository;

    private RatingService $ratingService;

    private const BOOK_ID = 1;

    private const PER_PAGE = 5;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reviewRepository = $this->createMock(ReviewRepository::class);
        $this->ratingService = $this->createMock(RatingService::class);
    }


    public static function provider(): array
    {
        return [
            [0, 0],
            [-1, 0],
            [-20, 0]
        ];
    }


    #[DataProvider('provider')]
    public function testGetReviewsByBookIdInvalid(int $page, int $offset): void
    {

        $this->ratingService
            ->expects($this->once())
            ->method('calculateReviewRatingFromBook')
            ->with(self::BOOK_ID, 0)
            ->willReturn(0.0);

        $this->reviewRepository
            ->expects($this->once())
            ->method('getPageByBookId')
            ->with(self::BOOK_ID, $offset, self::PER_PAGE)
            ->willReturn(new \ArrayIterator());

        $service = new ReviewService($this->reviewRepository, $this->ratingService);
        $expected = (new ReviewPage())
            ->setTotal(0)
            ->setRating(0)
            ->setPage($page)
            ->setPages(0)
            ->setPerPage(self::PER_PAGE)
            ->setItems([]);

        $this->assertEquals($expected, $service->getReviewsByBookId(self::BOOK_ID, $page));
    }


    public function testGetReviewsByBookId(): void
    {
        $this->ratingService
            ->expects($this->once())
            ->method('calculateReviewRatingFromBook')
            ->with(self::BOOK_ID, 1)
            ->willReturn(4.0);

        $review = (new Review())
            ->setContent('SSS')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setRating(4)
            ->setAuthor('John Connor');

        $this->setEntityId($review, self::BOOK_ID);

        $this->reviewRepository
            ->expects($this->once())
            ->method('getPageByBookId')
            ->with(self::BOOK_ID, 0, self::PER_PAGE)
            ->willReturn(new \ArrayIterator([$review]));

        $service = new ReviewService($this->reviewRepository, $this->ratingService);
        $expected = (new ReviewPage())
            ->setTotal(1)
            ->setRating(4.0)
            ->setPage(1)
            ->setPages(1)
            ->setPerPage(self::PER_PAGE)
            ->setItems([(new \App\Model\Review())
                ->setContent($review->getContent())
                ->setCreatedAt($review->getCreatedAt()->getTimestamp())
                ->setRating($review->getRating())
                ->setAuthor($review->getAuthor())
                ->setId($review->getId())
            ]);


        $this->assertEquals($expected, $service->getReviewsByBookId(self::BOOK_ID, 1));



    }
}

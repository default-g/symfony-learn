<?php

namespace App\Tests\Service;

use App\Repository\ReviewRepository;
use App\Service\Rating;
use App\Service\RatingService;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RatingServiceTest extends AbstractTestCase
{
    private ReviewRepository $reviewRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reviewRepository = $this->createMock(ReviewRepository::class);
    }


    public static function provider(): array
    {
        return [
            [25, 20, 1.25],
            [0, 5, 0],

        ];
    }


    #[DataProvider('provider')]
    public function testCalculateReviewRatingFromBook(int $repositoryRatingSum, int $total, float $expected)
    {
        $this->reviewRepository->expects($this->once())
            ->method('getBookTotalRatingSum')
            ->with(1)
            ->willReturn($repositoryRatingSum);

        $this->reviewRepository->expects($this->once())
            ->method('countByBookId')
            ->willReturn($total);

            $actual = (new RatingService($this->reviewRepository))
                ->calculateReviewRatingFromBook(1);

        $this->assertEquals(new Rating($total, $expected), $actual);
    }


    public function testCalculateReviewRatingFromBookWithZero()
    {
        $this->reviewRepository->expects($this->never())->method('getBookTotalRatingSum');

        $actual = (new RatingService($this->reviewRepository))->calculateReviewRatingFromBook(1);

        $this->assertEquals($actual, new Rating(0, 0));
    }
}

<?php

namespace App\Controller;

use App\Exception\BookCategoryNotFoundException;
use App\Model\ReviewPage;
use App\Service\BookService;
use App\Service\ReviewService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\BookListResponse;
use App\Model\ErrorResponse;
use App\Model\BookDetails;

class ReviewController extends AbstractController
{
    public function __construct (private readonly ReviewService $reviewService) {}

    /**
     * @OA\Parameter(name="page", in="query", description="Page number", @OA\Schema(type="integer"))
     * @OA\Response(
     *     response=200,
     *     description="Return page of reviews for book",
     *     @Model(type=ReviewPage::class)
     * )
     */
    #[Route(path: '/api/v1/book/{id}/reviews', name: 'reviews', methods: ['GET'])]
    public function action(int $id, Request $request): JsonResponse
    {
        return $this->json($this->reviewService->getReviewsByBookId(
            $id, $request->query->getInt('page', 1))
        );
    }

}

<?php

namespace App\Controller;

use App\Service\RecommendationService;
use OpenApi\Annotations as OA;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\RecommendedBookListResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
class RecommendationController extends AbstractController
{
    public function __construct(private RecommendationService $recommendationService) {}

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns recommendations by book id",
     *     @Model(type=RecommendedBookListResponse::class)
     * )
     */
    #[Route(path: '/api/v1/book/{id}/recommendations', name: 'recommendations', methods: ['GET'])]
    public function recommendations(int $id): Response
    {
        return $this->json($this->recommendationService->getRecommendationsByBookId($id));
    }

}

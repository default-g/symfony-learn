<?php

namespace App\Controller;

use App\Model\BookCategoryListResponse;
use App\Service\BookCategoryService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BookCategoryController extends AbstractController
{
    public function __construct(private readonly BookCategoryService $bookCategoryService)
    {
    }

    /**
     * @OA\Response(
     *      response=200,
     *      description="Returns a list of categories",
     *
     *      @Model(type=BookCategoryListResponse::class)
     * )
     */
    #[Route('/api/v1/book/categories', name: 'categories')]
    public function categories(): JsonResponse
    {
        return $this->json($this->bookCategoryService->getCategories());
    }
}

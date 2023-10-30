<?php

namespace App\Controller;

use App\Service\BookCategoryService;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\BookCategoryListResponse;

class BookCategoryController extends  AbstractController
{
    public function __construct(private readonly BookCategoryService $bookCategoryService)
    {

    }


    /**
     * @OA\Response(
     *      response=200,
     *      description="Returns a list of categories",
     *      @Model(type=BookCategoryListResponse::class)
     * )
     */
    #[Route('/api/v1/bookCategories', name: 'categories')]
    public function categories(): JsonResponse
    {
        return $this->json($this->bookCategoryService->getCategories());
    }
}

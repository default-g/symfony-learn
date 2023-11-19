<?php

namespace App\Controller;

use App\Exception\BookCategoryNotFoundException;
use App\Service\BookService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\BookListResponse;
use App\Model\ErrorResponse;
use App\Model\BookDetails;

class BookController extends AbstractController
{
    public function __construct(private readonly BookService $bookService) {}

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns books inside category",
     *     @Model(type=BookListResponse::class)
     * )
     *
     * @OA\Response(
     *     response=404,
     *     description="Book category not found",
     *     @Model(type=ErrorResponse::class)
     * )
     */
    #[Route('/api/v1/category/{id}/books', name: 'books', methods: ['GET'])]
    public function booksByCategory(int $id): JsonResponse
    {
        return $this->json($this->bookService->getBooksByCategory($id));
    }


    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns book information",
     *     @Model(type=BookDetails::class)
     * )
     *
     * @OA\Response(
     *     response=404,
     *     description="Book not found",
     *     @Model(type=ErrorResponse::class)
     * )
     */
    #[Route('/api/v1/book/{id}', name: 'book', methods: ['GET'])]
    public function getBook(int $id): JsonResponse
    {
        return $this->json($this->bookService->getBookById($id));
    }



}

<?php

namespace App\Controller;

use App\Exception\BookCategoryNotFoundException;
use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    public function __construct(private readonly BookService $bookService)
    {
    }

    #[Route('/api/v1/category/{id}/books', name: 'books')]
    public function booksByCategory(int $id): JsonResponse
    {
        try {
            return $this->json($this->bookService->getBooksByCategory($id));
        } catch (BookCategoryNotFoundException $exception) {
            throw new HttpException($exception->getCode(), $exception->getMessage());
        }
    }
}

<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\BookCategoryUpdateRequest;
use App\Service\BookCategoryService;
use App\Service\RoleService;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Model\ErrorResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\IdResponse;

class AdminController extends AbstractController
{
    public function __construct(
        private readonly RoleService $roleService,
        private readonly BookCategoryService $bookCategoryService
    ) {}

    /**
     * @OA\Tag(name="Admin API")
     * @OA\Response(
     *     response=200,
     *     description="Grants ROLE_AUTHOR to a user",
     *     @Model(type=IdResponse::class)
     * )
     * @OA\Response(
     *        response=404,
     *        description="User not found",
     *        @Model(type=ErrorResponse::class)
     *    )
     */
    #[Route(path: '/api/v1/admin/grantAuthor/{userId}', methods: ['POST'])]
    public function grantAuthor(int $userId): Response
    {
        $this->roleService->grantAdmin($userId);

        return $this->json(null);
    }


    /**
     * @OA\Tag(name="Admin API")
     * @OA\Response(
     *     response=200,
     *     description="Delete book category"
     * )
     * @OA\Response(
     *        response=404,
     *        description="Book category not found",
     *        @Model(type=ErrorResponse::class)
     *    )
     * @OA\Response(
     *     response=400,
     *     description="Book category still contains books",
     *     @Model(type=ErrorResponse::class)
     * )
     */
    #[Route(path: '/api/v1/admin/bookCategory/{id}', methods: ['DELETE'])]
    public function deleteCategory(int $id): Response
    {
        $this->bookCategoryService->deleteCategory($id);

        return $this->json(null);
    }


    /**
     * @OA\Tag(name="Admin API")
     * @OA\Response(
     *     response=200,
     *     description="Create book category"
     * )
     * @OA\Response(
     *      response=400,
     *      description="Validation failed",
     *      @Model(type=ErrorResponse::class)
     *  )
     * @OA\Response(
     *     response=409,
     *     description="Book category already exists",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\RequestBody(@Model(type=BookCategoryUpdateRequest::class))
     */
    #[Route(path: '/api/v1/admin/bookCategory', methods: ['POST'])]
    public function createCategory(#[RequestBody] BookCategoryUpdateRequest $request): Response
    {
        return $this->json($this->bookCategoryService->createCategory($request));
    }


    /**
     * @OA\Tag(name="Admin API")
     * @OA\Response(
     *     response=200,
     *     description="Update a book category"
     * )
     * @OA\Response(
     *      response=400,
     *      description="Validation failed",
     *      @Model(type=ErrorResponse::class)
     *  )
     * @OA\Response(
     *     response=409,
     *     description="Book category already exists",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\RequestBody(@Model(type=BookCategoryUpdateRequest::class))
     */
    #[Route(path: '/api/v1/admin/bookCategory/{id}', methods: ['POST'])]
    public function updateCategory(int $id, #[RequestBody] BookCategoryUpdateRequest $request): Response
    {
        $this->bookCategoryService->updateCategory($id, $request);

        return $this->json(null);
    }
}

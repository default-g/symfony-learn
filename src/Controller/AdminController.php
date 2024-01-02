<?php

namespace App\Controller;
use App\Service\RoleService;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Model\ErrorResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    public function __construct(private RoleService $roleService)
    {
    }

    /**
     * @OA\Tag(name="Admin API")
     * @OA\Response(
     *     response=200,
     *     description="Grants ROLE_AUTHOR to a user"
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
}

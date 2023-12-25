<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\IdResponse;
use App\Model\SignUpRequest;
use App\Service\SignUpService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\ErrorResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class AuthController extends AbstractController
{
    public function __construct(private SignUpService $signUpService)
    {
    }


    /**
     * @OA\Response(
     *     response=200,
     *     description="Signs up as user",
     *     @Model(type=IdResponse::class)
     * )
     * @OA\Response(
     *      response=409,
     *      description="User already exists",
     *      @Model(type=ErrorResponse::class)
     *  )
     * @OA\Response(
     *       response=400,
     *       description="Validation failed",
     *       @Model(type=ErrorResponse::class)
     *   )
     * @OA\RequestBody(@Model(type=SignUpRequest::class))
     */
    #[Route(path: '/api/v1/signUp', methods: ['POST'])]
    public function signUp(#[RequestBody] SignUpRequest $signUpRequest): Response
    {
        return $this->json($this->signUpService->signUp($signUpRequest));
    }


    #[Route(path: '/api/v1/user/me', methods: ['GET'])]
    public function me(#[CurrentUser] UserInterface $user): Response
    {
        return $this->json($user);
    }

}

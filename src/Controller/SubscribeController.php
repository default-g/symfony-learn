<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\SubscriberRequest;
use App\Service\SubscriberService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\ErrorResponse;
class SubscribeController extends AbstractController
{
    public function __construct(private readonly SubscriberService $subscriberService)
    {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Subscribe email to the newsletter"
     * )
     * @OA\Response(
     *     response=400,
     *     description="Verification failed",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\RequestBody(@Model(type=SubscriberRequest::class))
     */
    #[Route('/api/v1/subscribe', name: 'subscribe', methods: ['POST'])]
    public function subscribe(#[RequestBody] SubscriberRequest $subscriberRequest): Response
    {
        $this->subscriberService->subscribe($subscriberRequest);

        return $this->json(null);
    }
}

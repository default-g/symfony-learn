<?php

namespace App\Service\Recommendation;

use App\Service\Recommendation\Exception\AccessDeniedException;
use App\Service\Recommendation\Exception\RequestException;
use App\Service\Recommendation\Model\RecommendationResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RecommendationApiService
{
    public function __construct(
        private readonly HttpClientInterface $recommendationsClient,
        private readonly SerializerInterface $serializer
    ){}

    /**
     * @throws RequestException
     * @throws AccessDeniedException
     */
    public function getRecommendationsByBookId(int $id): RecommendationResponse
    {
        try {
            $response = $this->recommendationsClient->request('GET', '/api/v1/book/' . $id . '/recommendations');
        } catch (\Throwable $exception) {
            if ($exception instanceof TransportExceptionInterface && Response::HTTP_FORBIDDEN === $exception->getCode()) {
                throw new AccessDeniedException();
            }

            throw new RequestException($exception->getMessage());
        }

        return $this->serializer->deserialize(
            $response->getContent(),
            RecommendationResponse::class,
            JsonEncoder::FORMAT
        );
    }
}

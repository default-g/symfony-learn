<?php

namespace App\Tests\Service\Recommendation;

use App\Service\Recommendation\Exception\AccessDeniedException;
use App\Service\Recommendation\Exception\RequestException;
use App\Service\Recommendation\RecommendationApiService;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class RecommendationApiServiceTest extends AbstractTestCase
{

    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = $this->createMock(SerializerInterface::class);
    }

    public function dataProvider(): array
    {
        return [
            [Response::HTTP_FORBIDDEN, AccessDeniedException::class],
            [Response::HTTP_CONFLICT, RequestException::class]
        ];
    }

    #[DataProvider('dataProvider')]
    public function testGetRecommendationsByBookId(int $responseCode, string $exceptionClass)
    {
        $this->expectException($exceptionClass);

        $httpClient = new MockHttpClient(
            new MockResponse(
                '',
                ['http_code' => $responseCode]
            ),
            'http://localhost/'
        );

        (new RecommendationApiService($httpClient, $this->serializer))->getRecommendationsByBookId(1);

    }
}

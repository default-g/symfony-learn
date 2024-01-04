<?php

namespace App\Tests\Controller;

use App\Controller\ReviewController;
use App\Entity\Book;
use App\Entity\Review;
use App\Entity\User;
use App\Tests\AbstractControllerTestCase;
use Hoverfly\Model\RequestFieldMatcher;
use Hoverfly\Model\Response;
use PHPUnit\Framework\TestCase;
use Hoverfly\Client as HoverflyClient;

class RecommendationControllerTest extends AbstractControllerTestCase
{
    private HoverflyClient $hoverFlyClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpHoverfly();
    }

    public function testRecommendationByBookId(): void
    {
        $bookId = $this->createBook();
        $requestId = 123;

        $this->hoverFlyClient->simulate(
            $this->hoverFlyClient->buildSimulation()
            ->service()
            ->get(new RequestFieldMatcher("/api/v1/book/$requestId/recommendations", RequestFieldMatcher::GLOB))
            ->headerExact('Authorization', 'Bearer test')
            ->willReturn(Response::json([
                'ts' => 12345,
                'id' => $requestId,
                'recommendations' => [['id' => $bookId]]
            ]))
        );

        $this->entityManager->flush();
        $this->client->request('GET', "/api/v1/book/$requestId/recommendations");

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['items'],
            'properties' => [
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug', 'image', 'shortDescription'],
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'image' => ['type' => 'string'],
                            'shortDescription' => ['type' => 'string'],
                        ]
                    ],
                ]
            ]
        ]);

    }


    private function createBook(): int
    {
        $user = $this->createAuthor('admin', '123');

        $book = (new Book())
            ->setTitle('test')
            ->setImage('image.png')
            ->setMeap(false)
            ->setIsbn('21123')
            ->setDescription('321321')
            ->setPublicationDate(new \DateTimeImmutable())
            ->setAuthors(['me'])
            ->setUser($user)
            ->setSlug('test');

        $this->entityManager->persist($book);
        $this->entityManager->flush();;

        return $book->getId();
    }


    private function setUpHoverfly(): void
    {
        $this->hoverFlyClient = new HoverflyClient(['base_uri' => $_ENV['HOVERFLY_API']]);
        $this->hoverFlyClient->deleteJournal();
        $this->hoverFlyClient->deleteSimulation();
    }

}


<?php

namespace App\Tests\Controller;

use App\Controller\ReviewController;
use App\Entity\Book;
use App\Entity\Review;
use App\Tests\AbstractControllerTestCase;
use PHPUnit\Framework\TestCase;

class ReviewControllerTest extends AbstractControllerTestCase
{
    public function testCase(): void
    {
        $book = $this->createBook();
        $this->createReview($book);

        $this->entityManager->flush();
        $this->client->request('GET', "/api/v1/book/{$book->getId()}/reviews", ['page' => 1]);

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['items', 'rating', 'pages', 'page', 'perPage', 'total'],
            'properties' => [
                'rating' => ['type' => 'number'],
                'page' => ['type' => 'integer'],
                'pages' => ['type' => 'integer'],
                'perPage' => ['type' => 'integer'],
                'total' => ['type' => 'integer'],
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'author', 'rating', 'content', 'createdAt'],
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'author' => ['type' => 'string'],
                            'rating' => ['type' => 'number'],
                            'content' => ['type' => 'string'],
                            'createdAt' => ['type' => 'integer'],
                        ]
                    ],
                ]
            ]
        ]);

    }


    private function createBook(): Book
    {
        $book = (new Book())
            ->setTitle('test')
            ->setImage('image.png')
            ->setMeap(false)
            ->setIsbn('21123')
            ->setDescription('321321')
            ->setPublicationDate(new \DateTimeImmutable())
            ->setAuthors(['me'])
            ->setSlug('test');

        $this->entityManager->persist($book);

        return $book;
    }


    private function createReview(Book $book): Review
    {
        $review = (new Review())
            ->setAuthor('me')
            ->setRating(5)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setContent('DDD')
            ->setBook($book);

        $this->entityManager->persist($review);

        return $review;
    }

}


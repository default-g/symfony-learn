<?php

namespace App\Tests\Controller;

use App\Entity\BookCategory;
use App\Tests\AbstractControllerTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class   BookCategoryControllerTest extends AbstractControllerTestCase
{
    public function testCategories()
    {
        $this->entityManager->persist((new BookCategory())->setTitle('DDD')->setSlug('DDD'));
        $this->entityManager->flush();

        $this->client->request('GET', '/api/v1/book/categories');
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
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                        ]
                    ]
                ],
            ],
        ]);
    }
}

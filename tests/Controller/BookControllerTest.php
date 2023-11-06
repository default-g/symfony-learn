<?php

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends AbstractControllerTestCase
{
    public function testCategories()
    {
        $this->client->request('GET', '/api/v1/category/1/books');

        $this->assertResponseIsSuccessful();

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

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
                            'authors' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'string'
                                ]
                            ],
                            'publicationDate' => ['type' => 'integer'],
                            'meap' => ['type' => 'boolean'],
                            'image' => ['type' => 'string'],
                            'categories' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'id' => ['type' => 'integer'],
                                        'title' => ['type' => 'string'],
                                        'slug' => ['type' => 'string']
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }
}

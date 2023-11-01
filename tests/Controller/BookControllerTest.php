<?php

namespace App\Tests\Controller;

use App\Controller\BookCategoryController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    public function testCategories()
    {
        $client = static::createClient();
        $client->request('GET', "/api/v1/category/1/books");

        $responseContent = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();

        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/responses/BookControllerTest_testBooksByCategory.json',
            $responseContent
        );

    }

}

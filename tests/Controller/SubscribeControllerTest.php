<?php

namespace App\Tests\Controller;

use App\Controller\SubscribeController;
use App\Tests\AbstractControllerTestCase;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class SubscribeControllerTest extends AbstractControllerTestCase
{
    public function testSubscribe()
    {
        $content = json_encode(['email' => 'test@test.com', 'agreed' => true]);
        $this->client->request('POST', 'api/v1/subscribe', [], [], [], $content);

        $this->assertResponseIsSuccessful();
    }


    public function testSubscribeNotAgreed(): void
    {
        $content = json_encode(['email' => 'test@test.com', 'agreed' => false]);
        $this->client->request('POST', 'api/v1/subscribe', [], [], [], $content);
        $responseContent = json_decode($this->client->getResponse()->getContent());

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJsonDocumentMatches($responseContent, [
            '$.message' => 'validation failed',
            '$.details.violations' => self::countOf(1),
            '$.details.violations[0].field' => 'agreed',
        ]);

    }

}

<?php

namespace App\Tests\Controller;

use App\Controller\SubscribeController;
use App\Tests\AbstractControllerTestCase;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class AuthControllerTest extends AbstractControllerTestCase
{

    public function testSignUp(): void
    {
        $this->client->request('POST', '/api/v1/signUp', [], [], [], json_encode([
            'firstName' => 'TEST',
            'lastName' => 'TEST',
            'email' => 'example@example.com',
            'password' => 'testtest',
            'confirmPassword' => 'testtest'
        ]));

        $responseContent = json_decode($this->client->getResponse()->getContent());

//        dd($responseContent);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['token', 'refresh_token'],
            'properties' => [
                'token' => ['type' => 'string'],
                'refresh_token' => ['type' => 'string']
            ]
        ]);
    }

}

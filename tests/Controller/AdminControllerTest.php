<?php

namespace App\Tests\Controller;

use App\Controller\SubscribeController;
use App\Tests\AbstractControllerTestCase;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminControllerTest extends AbstractControllerTestCase
{

    public function testGrantAuthor(): void
    {
        $username = 'test@test.com';
        $password = 'testtest';

        $user = $this->createAdmin($username, $password);

        $this->auth($username, $password);

        $this->client->request('POST', '/api/v1/admin/grantAuthor/' . $user->getId());

        $this->assertResponseIsSuccessful();
    }

}

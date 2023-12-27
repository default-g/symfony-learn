<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Helmich\JsonAssert\JsonAssertions;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractControllerTestCase extends WebTestCase
{
    use JsonAssertions;

    protected KernelBrowser $client;

    protected ?EntityManager $entityManager;

    protected UserPasswordHasherInterface $hasher;


    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->entityManager = self::getContainer()->get('doctrine.orm.entity_manager');

        $this->hasher = self::getContainer()->get('security.user_password_hasher');

    }


    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;

    }


    protected function auth(string $username, string $password)
    {
        $this->client->request('POST',
            '/api/v1/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => $username,
                'password' => $password
            ]));

        $this->assertResponseIsSuccessful();
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));


    }


    protected function createAdmin(string $username, string $password): User
    {
        return $this->createUser($username, $password, ['ROLE_ADMIN']);
    }


    protected function createAuthor(string $username, string $password): User
    {
        return $this->createUser($username, $password, ['ROLE_AUTHOR']);
    }


    private function createUser(string $username, string $password, array $roles): User
    {
        $user = (new User())
            ->setRoles($roles)
            ->setEmail($username)
            ->setLastName($username)
            ->setFirstName($username);

        $user->setPassword($this->hasher->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

}

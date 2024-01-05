<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\RoleService;
use App\Tests\AbstractTestCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class RoleServiceTest extends AbstractTestCase
{
    private UserRepository $userRepository;

    private EntityManagerInterface $entityManager;

    private User $user;


    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User();

        $this->setEntityId($this->user, 1);

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with(1)
            ->willReturn($this->user);

        $this->userRepository
            ->expects($this->once())
            ->method('commit');
    }

    private function createService(): RoleService
    {
        return new RoleService($this->userRepository);
    }

    public function testGrantAdmin(): void
    {
        $this->createService()->grantAdmin($this->user->getId());
        $this->assertEquals(['ROLE_ADMIN'], $this->user->getRoles());
    }

    public function testGrantAuthor(): void
    {
        $this->createService()->grantAuthor($this->user->getId());
        $this->assertEquals(['ROLE_AUTHOR'], $this->user->getRoles());
    }
}

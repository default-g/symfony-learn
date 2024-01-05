<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class RoleService
{
    public function __construct(private UserRepository $userRepository)
    {
    }


    public function grantAdmin(int $userId): void
    {
        $this->grantRole($userId, 'ROLE_ADMIN');
    }


    public function grantAuthor(int $userId): void
    {
        $this->grantRole($userId, 'ROLE_AUTHOR');
    }


    private function grantRole(int $userId, string $role): void
    {
        /** @var User $user */
        $user = $this->userRepository->getUser($userId);
        $user->setRoles([$role]);

        $this->userRepository->commit();
    }

}

<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookFormat;
use App\Entity\User;
use App\Exception\UserNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


    public function existsByEmail(string $email): bool
    {
        return null !== $this->findOneBy(['email' => $email]);
    }


    public function getUser(int $userId): User
    {
        $user = $this->find($userId);

        if (null === $user) {
            throw new UserNotFoundException();
        }

        return $user;
    }


    public function saveAndCommit(User $user): void
    {
        $this->save($user);
        $this->commit();
    }


    public function save(User $user): void
    {
        $this->getEntityManager()->persist($user);
    }


    public function commit(): void
    {
        $this->getEntityManager()->flush();
    }
}

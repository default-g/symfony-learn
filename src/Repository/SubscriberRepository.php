<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\Subscriber;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subscriber>
 *
 * @method Subscriber|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscriber|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscriber[]    findAll()
 * @method Subscriber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscriber::class);
    }


    public function existsByEmail(string $email): bool
    {
        return null !== $this->findOneBy(['email' => $email]);
    }


    public function saveAndCommit(Subscriber $subscriber): void
    {
        $this->save($subscriber);
        $this->commit();
    }


    public function save(Subscriber $subscriber): void
    {
        $this->getEntityManager()->persist($subscriber);
    }


    public function commit(): void
    {
        $this->getEntityManager()->flush();
    }
}

<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookFormat;
use App\Entity\BookToBookFormat;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookFormat>
 *
 * @method BookToBookFormat|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookToBookFormat|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookToBookFormat[]    findAll()
 * @method BookToBookFormat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookToBookFormatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookToBookFormat::class);
    }
}

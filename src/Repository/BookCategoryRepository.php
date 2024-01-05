<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Exception\BookCategoryNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method BookCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookCategory[]    findAll()
 * @method BookCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookCategory::class);
    }


    public function remove(BookCategory $bookCategory): void
    {
        $this->getEntityManager()->remove($bookCategory);
    }


    public function removeAndCommit(BookCategory $bookCategory): void
    {
        $this->remove($bookCategory);
        $this->commit();
    }

    public function saveAndCommit(BookCategory $bookCategory): void
    {
        $this->save($bookCategory);
        $this->commit();
    }


    public function save(BookCategory $bookCategory): void
    {
        $this->getEntityManager()->persist($bookCategory);
    }


    public function commit(): void
    {
        $this->getEntityManager()->flush();
    }


    /** @return BookCategory[] */
    public function findBookCategoriesByIds(array $ids): array
    {
        return $this->findBy(['id' => $ids]);
    }


    public function findAllSortedByTitle(): array
    {
        return $this->findBy([], ['title' => Criteria::ASC]);
    }


    public function existsById(int $id): bool
    {
        return null !== $this->find($id);
    }


    public function getById(int $id): BookCategory
    {
        $bookCategory = $this->find($id);

        if (null === $bookCategory) {
            throw new BookCategoryNotFoundException();
        }

        return $bookCategory;
    }


    public function countBooksInCategory(int $id): int
    {
        return $this->_em->createQuery('SELECT COUNT(b.id) FROM App\Entity\Book b WHERE :id MEMBER OF b.categories')
            ->setParameter('id', $id)
            ->getSingleScalarResult();
    }


    public function existsBySlug(string $slug): bool
    {
        return null !== $this->findOneBy(['slug' => $slug]);
    }
}

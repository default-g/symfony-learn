<?php

namespace App\Tests\Repository;

use App\Entity\BookCategory;
use App\Repository\BookCategoryRepository;
use App\Tests\AbstractRepositoryTestCase;

class BookCategoryRepositoryTestCase extends AbstractRepositoryTestCase
{
    private BookCategoryRepository $bookCategoryRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookCategoryRepository = $this->getRepositoryForEntity(BookCategory::class);
    }

    public function testFindAllSortedByTitle(): void
    {
        $categories = [];
        for ($i = 0; $i < 10; ++$i) {
            $bookCategory = (new BookCategory())
                ->setTitle('AAA'.$i)
                ->setSlug('AAA'.$i);

            $categories[] = $bookCategory;

            $this->entityManager->persist($bookCategory);

        }

        $this->entityManager->flush();

        $this->assertEquals($categories, $this->bookCategoryRepository->findAllSortedByTitle());
    }
}

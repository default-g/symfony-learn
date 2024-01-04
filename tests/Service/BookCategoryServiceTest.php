<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Model\BookCategory as BookCategoryModel;
use App\Model\BookCategoryListResponse;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Service\BookCategoryService;
use App\Service\BookService;
use App\Tests\AbstractTestCase;
use Doctrine\ORM\EntityManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class BookCategoryServiceTest extends AbstractTestCase
{
    public function testGetCategories(): void
    {
        $bookCategory = (new BookCategory())
            ->setTitle('AAA')
            ->setSlug('AAA');

        $this->setEntityId($bookCategory, 7);

        $slugger = $this->createMock(SluggerInterface::class);

        $entityManager = $this->createMock(EntityManager::class);

        $repository = $this->createMock(BookCategoryRepository::class);
        $repository->expects($this->once())
            ->method('findAllSortedByTitle')
            ->willReturn([
               $bookCategory,
            ]);

        $service = new BookCategoryService($repository, $entityManager, $slugger);
        $expected = new BookCategoryListResponse([new BookCategoryModel(7, 'AAA', 'AAA')]);

        $this->assertEquals($expected, $service->getCategories());
    }

}

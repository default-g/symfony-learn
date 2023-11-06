<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Model\BookCategoryListItem;
use App\Model\BookCategoryListResponse;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Service\BookCategoryService;
use App\Service\BookService;
use App\Tests\AbstractTestCase;
use Doctrine\Common\Collections\Criteria;
use PHPUnit\Framework\TestCase;

class BookCategoryServiceTest extends AbstractTestCase
{

    public function testGetCategories(): void
    {

        $bookCategory =  (new BookCategory())
            ->setTitle('AAA')
            ->setSlug('AAA');

        $this->setEntityId($bookCategory, 7);

        $repository = $this->createMock(BookCategoryRepository::class);
        $repository->expects($this->once())
            ->method('findBy')
            ->with([], ['title' => Criteria::ASC])
            ->willReturn([
               $bookCategory
            ]);

        $service = new BookCategoryService($repository);
        $expected = new BookCategoryListResponse([new BookCategoryListItem(7, 'AAA', 'AAA')]);

        $this->assertEquals($expected, $service->getCategories());
    }

    public function testGetBooks(): void
    {
        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->once())
            ->method('findBooksByCategory')
            ->with(130)
            ->willReturn([$this->createBookEntity()]);

        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $bookCategoryRepository->expects($this->once())
            ->method('find')
            ->with(130)
            ->willReturn(new BookCategory());

        $service = new BookService($bookRepository, $bookCategoryRepository);
        $expected = new BookListResponse([$this->createBookListItem()]);

        $this->assertEquals($expected, $service->getBooksByCategory(130));
    }


    private function createBookEntity(): Book
    {
        $book = (new Book)
            ->setTitle('New Book')
            ->setImage('image.jpg')
            ->setSlug('new-book')
            ->setPublicationDate(new \DateTime())
            ->setAuthors(['Bob'])
            ->setMeap(true);

        $this->setEntityId($book, 1);

        return $book;
    }

    private function createBookListItem(): BookListItem
    {
        $book = $this->createBookEntity();

        return (new BookListItem())
            ->setId($book->getId())
            ->setPublicationDate($book->getPublicationDate()->getTimestamp())
            ->setTitle($book->getTitle())
            ->setSlug($book->getSlug())
            ->setAuthors($book->getAuthors())
            ->setImage($book->getImage())
            ->setMeap($book->getImage())
            ->setAuthors($book->getAuthors());

    }
}

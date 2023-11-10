<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Service\BookService;
use App\Tests\AbstractTestCase;
use Doctrine\Common\Collections\ArrayCollection;

class BookServiceTest extends AbstractTestCase
{
    public function testGetBooksByCategoryNotFound()
    {
        $bookRepository = $this->createMock(BookRepository::class);
        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(false);

        $this->expectException(BookCategoryNotFoundException::class);

        (new BookService($bookRepository, $bookCategoryRepository))->getBooksByCategory(130);
    }


    public function testGetBooksByCategory()
    {
        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->once())
            ->method('findBooksByCategory')
            ->with(130)
            ->willReturn([$this->createBookEntity()]);

        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(true);

        $service = new BookService($bookRepository, $bookCategoryRepository);
        $expected = new BookListResponse([$this->createBookItemModel()]);

        $this->assertEquals($expected, $service->getBooksByCategory(130));
    }

    private function createBookEntity(): Book
    {
        $book = (new Book())
            ->setTitle('Cool book')
            ->setSlug('cool-book')
            ->setMeap(false)
            ->setAuthors(['TEST'])
            ->setImage('NO IMAGE')
            ->setCategories(new ArrayCollection())
            ->setPublicationDate(new \DateTime());

        $this->setEntityId($book, 1);

        return $book;
    }

    private function createBookItemModel(): BookListItem
    {
        $book = $this->createBookEntity();
        return (new BookListItem())
            ->setId($book->getId())
            ->setImage($book->getImage())
            ->setTitle($book->getTitle())
            ->setAuthors($book->getAuthors())
            ->setMeap($book->isMeap())
            ->setPublicationDate($book->getPublicationDate()->getTimestamp())
            ->setSlug($book->getSlug());
    }
}

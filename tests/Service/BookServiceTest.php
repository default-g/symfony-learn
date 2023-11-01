<?php

namespace App\Tests\Service;

use App\Exception\BookCategoryNotFoundException;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Service\BookService;
use PHPUnit\Framework\TestCase;

class BookServiceTest extends TestCase
{

    public function testGetBooksByCategoryNotFound()
    {
        $bookRepository = $this->createMock(BookRepository::class);
        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $bookCategoryRepository->expects($this->once())
            ->method('find')
            ->with(130)
            ->willThrowException(new BookCategoryNotFoundException());

        $this->expectException(BookCategoryNotFoundException::class);

        (new BookService($bookRepository, $bookCategoryRepository))->getBooksByCategory(130);
    }
}

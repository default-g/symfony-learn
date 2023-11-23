<?php

namespace App\Tests\Mapper;

use App\Entity\Book;
use App\Mapper\BookMapper;
use App\Model\BookDetails;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\TestCase;

class BookMapperTest extends AbstractTestCase
{

    public function testMap()
    {
        $book = (new Book())
            ->setIsbn('23123')
            ->setDescription('DDD')
            ->setMeap(false)
            ->setPublicationDate(new \DateTimeImmutable('2022-01-01'))
            ->setTitle('BOOK')
            ->setImage('image.png')
            ->setSlug('BOOK')
            ->setAuthors(['test']);

        $this->setEntityId($book, 1);

        $expected = (new BookDetails())
            ->setId(1)
            ->setMeap(false)
            ->setPublicationDate((new \DateTimeImmutable('2022-01-01'))->getTimestamp())
            ->setTitle('BOOK')
            ->setImage('image.png')
            ->setSlug('BOOK')
            ->setAuthors(['test']);

        $this->assertEquals($expected, BookMapper::map($book, new BookDetails()));




    }
}

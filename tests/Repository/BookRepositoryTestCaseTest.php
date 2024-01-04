<?php

namespace App\Tests\Repository;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Tests\AbstractRepositoryTestCase;
use Doctrine\Common\Collections\ArrayCollection;

class BookRepositoryTestCaseTest extends AbstractRepositoryTestCase
{
    private BookRepository $bookRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->getRepositoryForEntity(Book::class);
    }

    public function testFindPublishedBooksByCategoryId(): void
    {
        $category = (new BookCategory())
            ->setSlug('aaa')
            ->setTitle('AAA');

        $this->entityManager->persist($category);

        $user = $this->createUser();
        $this->entityManager->persist($user);

        for ($i = 0; $i < 10; ++$i) {
            $book = $this->createBook('AAA'.$i, [$category]);
            $book->setUser($user);
            $this->entityManager->persist($book);
        }

        $this->entityManager->flush();

        $this->assertCount(10, $this->bookRepository->findPublishedBooksByCategoryId($category->getId()));
    }


    private function createUser(): User
    {
        return (new User())
            ->setFirstName('A')
            ->setLastName('A')
            ->setRoles(['ROLE_AUTHOR'])
            ->setPassword('123qwe123')
            ->setEmail('admin@admin.com');
    }

    private function createBook(string $title, array $categories): Book
    {
        return (new Book())
            ->setTitle($title)
            ->setCategories(new ArrayCollection($categories))
            ->setSlug($title)
            ->setPublicationDate(new \DateTimeImmutable())
            ->setMeap(false)
            ->setImage('image.png')
            ->setAuthors(['me'])
            ->setDescription('fdfdf')
            ->setIsbn('aaaa');
    }
}

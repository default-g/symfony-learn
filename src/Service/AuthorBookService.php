<?php

namespace App\Service;

use App\Entity\Book;
use App\Exception\BookAlreadyExistsException;
use App\Model\Author\BookListItem;
use App\Model\Author\BookListResponse;
use App\Model\Author\CreateBookRequest;
use App\Model\Author\UploadCoverResponse;
use App\Model\PublishBookRequest;
use App\Repository\BookRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AuthorBookService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BookRepository         $bookRepository,
        private readonly SluggerInterface       $slugger,
        private readonly UploadService          $uploadService
    )
    {
    }


    public function uploadCover(int $id, UploadedFile $file): UploadCoverResponse
    {
        $book = $this->bookRepository->getBookById($id);
        $oldImage = $book->getImage();

        $link = $this->uploadService->uploadBookFile($id, $file);

        $book->setImage($link);

        $this->entityManager->flush();

        if (null !== $oldImage) {
            $this->uploadService->deleteBookFile($book->getId(), basename($oldImage));
        }

        return new UploadCoverResponse($link);
    }


    public function getBooks(UserInterface $user): BookListResponse
    {
        return new BookListResponse(
            array_map([$this, 'map'], $this->bookRepository->findUserBooks($user))
        );
    }


    public function createBook(CreateBookRequest $request, UserInterface $user): void
    {
        $slug = $this->slugger->slug($request->getTitle());

        if ($this->bookRepository->existsBySlug($slug)) {
            throw new BookAlreadyExistsException();
        }

        $book = (new Book())
            ->setTitle($request->getTitle())
            ->setSlug($slug)
            ->setMeap(false)
            ->setUser($user);

        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }


    public function deleteBook(int $id): void
    {
        $book = $this->bookRepository->getBookById($id);

        $this->entityManager->remove($book);
        $this->entityManager->flush();

    }


    private function map(Book $book): BookListItem
    {
        return (new BookListItem())
            ->setId($book->getId())
            ->setImage($book->getImage())
            ->setSlug($book->getSlug())
            ->setTitle($book->getTitle());
    }
}

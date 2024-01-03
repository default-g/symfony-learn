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
use Symfony\Component\String\Slugger\SluggerInterface;

class AuthorService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BookRepository         $bookRepository,
        private readonly SluggerInterface       $slugger,
        private readonly Security               $security,
        private readonly UploadService          $uploadService
    )
    {
    }


    public function uploadCover(int $id, UploadedFile $file): UploadCoverResponse
    {
        $book = $this->bookRepository->getUserBookById($id, $this->security->getUser());
        $oldImage = $book->getImage();

        $link = $this->uploadService->uploadBookFile($id, $file);

        $book->setImage($link);

        $this->entityManager->flush();

        if (null !== $oldImage) {
            $this->uploadService->deleteBookFile($book->getId(), basename($oldImage));
        }

        return new UploadCoverResponse($link);
    }


    public function publish(int $id, PublishBookRequest $request): void
    {
        $this->setPublicationDate($id, $request->getDate());
    }


    public function unpublish(int $id)
    {
        $this->setPublicationDate($id, null);
    }


    private function setPublicationDate(int $bookId, ?DateTimeInterface $dateTime): void
    {
        $book = $this->bookRepository->getUserBookById($bookId, $this->security->getUser());

        $book->setPublicationDate($dateTime);

        $this->entityManager->flush();
    }


    public function getBooks(): BookListResponse
    {
        return new BookListResponse(
            array_map([$this, 'map'], $this->bookRepository->findUserBooks($this->security->getUser()))
        );
    }


    public function createBook(CreateBookRequest $request): void
    {
        $slug = $this->slugger->slug($request->getTitle());

        if ($this->bookRepository->existsBySlug($slug)) {
            throw new BookAlreadyExistsException();
        }

        $book = (new Book())
            ->setTitle($request->getTitle())
            ->setSlug($slug)
            ->setMeap(false)
            ->setUser($this->security->getUser());

        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }


    public function deleteBook(int $id): void
    {
        $user = $this->security->getUser();
        $book = $this->bookRepository->getUserBookById($id, $user);

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

<?php

namespace App\Service;

use App\Model\PublishBookRequest;
use App\Repository\BookRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class BookPublishService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BookRepository         $bookRepository,
    ) {}

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
        $book = $this->bookRepository->getBookById($bookId);

        $book->setPublicationDate($dateTime);

        $this->entityManager->flush();
    }

}

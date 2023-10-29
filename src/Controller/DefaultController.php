<?php

namespace App\Controller;
use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly  EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/')] // Add a path within the parentheses, like #[Route('/')]
    public function root(): Response
    {

        $books = $this->bookRepository->findAll();

        return $this->json($books);
    }


    #[Route('/newBook')]
    public function newBook(): Response
    {
        $book = new Book();
        $book->setTitle('New Book');

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return new Response();

    }

}

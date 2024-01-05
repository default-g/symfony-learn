<?php

namespace App\Service;

use App\Entity\Book;
use App\Entity\BookToBookFormat;
use App\Exception\BookAlreadyExistsException;
use App\Mapper\BookMapper;
use App\Model\Author\BookDetails;
use App\Model\Author\BookFormatOptions;
use App\Model\Author\BookListItem;
use App\Model\Author\BookListResponse;
use App\Model\Author\CreateBookRequest;
use App\Model\Author\UpdateBookRequest;
use App\Model\Author\UploadCoverResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookFormatRepository;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AuthorBookService
{
    public function __construct(
        private readonly BookRepository         $bookRepository,
        private readonly BookFormatRepository   $bookFormatRepository,
        private readonly BookCategoryRepository $bookCategoryRepository,
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

        $this->bookRepository->commit();

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
        $slug = $this->slugifyOrThrow($request->getTitle());

        $book = (new Book())
            ->setTitle($request->getTitle())
            ->setSlug($slug)
            ->setMeap(false)
            ->setUser($user);

        $this->bookRepository->saveAndCommit($book);
    }


    public function deleteBook(int $id): void
    {
        $book = $this->bookRepository->getBookById($id);

        $this->bookRepository->removeAndCommit($book);
    }


    private function map(Book $book): BookListItem
    {
        return (new BookListItem())
            ->setId($book->getId())
            ->setImage($book->getImage())
            ->setSlug($book->getSlug())
            ->setTitle($book->getTitle());
    }


    public function getBook(int $id): BookDetails
    {
        $book = $this->bookRepository->getBookById($id);

        $bookDetails = (new BookDetails())
            ->setIsbn($book->getIsbn())
            ->setDescription($book->getDescription())
            ->setBookFormats(BookMapper::mapFormats($book))
            ->setCategories(BookMapper::mapCategories($book));

        return BookMapper::map($book, $bookDetails);
    }


    private function slugifyOrThrow(string $title): string
    {
        $slug = $this->slugger->slug($title);

        if ($this->bookRepository->existsBySlug($slug)) {
            throw new BookAlreadyExistsException();
        }

        return $slug;
    }


    public function updateBook(int $id, UpdateBookRequest $request): void
    {
        $book = $this->bookRepository->getBookById($id);
        $title = $request->getTitle();
        if (!empty($title)) {
            $book
                ->setTitle($title)
                ->setSlug($this->slugifyOrThrow($title));
        }

        $formats = array_map(function (BookFormatOptions $options) use ($book): BookToBookFormat {
            $format = (new BookToBookFormat())
                ->setPrice($options->getPrice())
                ->setBook($book)
                ->setDiscountPercent($options->getDiscountPercent())
                ->setBookFormat($this->bookFormatRepository->getById($options->getId()));

            $this->bookRepository->saveBookFormatReference($format);

            return $format;
        }, $request->getFormats());

        // TODO: Delete old formats

        $book
            ->setAuthors($request->getAuthors())
            ->setIsbn($request->getIsbn())
            ->setDescription($request->getDescription())
            ->setCategories(new ArrayCollection(
                $this->bookCategoryRepository->findBookCategoriesByIds($request->getCategories())
            ))
            ->setFormats(new ArrayCollection($formats));


        $this->bookRepository->commit();
    }
}

<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Attribute\RequestFile;
use App\Model\Author\CreateBookRequest;
use App\Model\PublishBookRequest;
use App\Security\Voter\AuthorBookVoter;
use App\Service\AuthorBookService;
use App\Service\BookPublishService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\ErrorResponse;
use App\Model\Author\BookListResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;
use App\Model\Author\UploadCoverResponse;

class AuthorController extends AbstractController
{
    public function __construct(
        private readonly AuthorBookService  $authorService,
        private readonly BookPublishService $bookPublishService
    )
    {
    }

    /**
     * @OA\Tag(name="Author API")
     * @OA\Response(
     *     response=200,
     *     description="Publish book"
     * )
     *
     * @OA\RequestBody(@Model(type=PublishBookRequest::class))
     * @OA\Response(
     *        response=400,
     *        description="Validation failed",
     *        @Model(type=ErrorResponse::class)
     *    )
     */
    #[Route(path: '/api/v1/author/book/{id}/publish', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'id')]
    public function publish(int $id, #[RequestBody] PublishBookRequest $request): Response
    {
        $this->bookPublishService->publish($id, $request);

        return $this->json(null);
    }


    /**
     * @OA\Tag(name="Author API")
     * @OA\Response(
     *     response=200,
     *     description="Upload book cover",
     *     @Model(type=UploadCoverResponse::class)
     * )
     *
     * @OA\Response(
     *        response=400,
     *        description="Validation failed",
     *        @Model(type=ErrorResponse::class)
     *    )
     */
    #[Route(path: '/api/v1/author/book/{id}/uploadCover')]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'id')]
    public function uploadCover(
        int              $id,
        #[RequestFile(field: 'cover', constraints: [
            new NotNull(),
            new Image(maxSize: '1M', mimeTypes: ['image/jpeg', 'image/png', 'image/jpg']),
        ])] UploadedFile $file
    ): Response
    {
        return $this->json($this->authorService->uploadCover($id, $file));
    }


    /**
     * @OA\Tag(name="Author API")
     * @OA\Response(
     *     response=200,
     *     description="Unpublish book"
     * )
     */
    #[Route(path: '/api/v1/author/book/{id}/unpublish', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'id')]
    public function unpublish(int $id): Response
    {
        $this->bookPublishService->unpublish($id);

        return $this->json(null);
    }


    /**
     * @OA\Tag(name="Author API")
     * @OA\Response(
     *     response=200,
     *     description="Get authors owned books",
     *     @Model(type=BookListResponse::class)
     * )
     */
    #[Route(path: '/api/v1/author/books', methods: ['GET'])]
    public function books(#[CurrentUser] UserInterface $user): Response
    {
        return $this->json($this->authorService->getBooks($user));
    }


    /**
     * @OA\Tag(name="Author API")
     * @OA\Response(
     *     response=200,
     *     description="Get authors owned books",
     *     @Model(type=BookListResponse::class)
     * )
     *
     * @OA\RequestBody(@Model(type=CreateBookRequest::class))
     * @OA\Response(
     *        response=404,
     *        description="Book not found",
     *        @Model(type=ErrorResponse::class)
     *    )
     */
    #[Route(path: '/api/v1/author/book', methods: ['POST'])]
    public function createBook(
        #[RequestBody] CreateBookRequest $request,
        #[CurrentUser] UserInterface $user
    ): Response
    {
        $this->authorService->createBook($request, $user);

        return $this->json(null);
    }


    /**
     * @OA\Tag(name="Author API")
     * @OA\Response(
     *     response=200,
     *     description="Get authors owned books",
     *     @Model(type=BookListResponse::class)
     * )
     * @OA\Response(
     *        response=404,
     *        description="Book not found",
     *        @Model(type=ErrorResponse::class)
     *    )
     */
    #[Route(path: '/api/v1/author/book/{id}', methods: ['DELETE'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'id')]
    public function deleteBook(int $id): Response
    {
        $this->authorService->deleteBook($id);

        return $this->json(null);
    }
}

<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Model\SignUpRequest;
use App\Repository\UserRepository;
use App\Service\SignUpService;
use App\Tests\AbstractTestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SignUpServiceTest extends AbstractTestCase
{
    private UserRepository $userRepository;

    private UserPasswordHasherInterface $userPasswordHasher;

    private EntityManagerInterface $entityManager;

    private AuthenticationSuccessHandler $authenticationSuccessHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userPasswordHasher = $this->createMock(UserPasswordHasher::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->authenticationSuccessHandler = $this->createMock(AuthenticationSuccessHandler::class);
    }


    public function createService(): SignUpService
    {
        return new SignUpService(
            $this->userRepository,
            $this->userPasswordHasher,
            $this->authenticationSuccessHandler
        );
    }


    public function testSignUpWhenUserWithGivenEmailExists(): void
    {
        $this->expectException(UserAlreadyExistsException::class);

        $this->userRepository->expects($this->once())
            ->method('existsByEmail')
            ->with('test@example.com')
            ->willReturn(true);

        $this->createService()->signUp(
            (new SignUpRequest())
                ->setEmail('test@example.com')
                ->setLastName('test')
                ->setFirstName('test')
                ->setPassword('12345678')
                ->setConfirmPassword('12345678')
        );

    }


    public function testSignUp(): void
    {
        $response = new Response();
        $expectedHasherUser = (new User())
            ->setRoles(['ROLE_USER'])
            ->setFirstName('test')
            ->setLastName('test')
            ->setEmail('test@example.com');

        $hasherUser = clone $expectedHasherUser;
        $hasherUser->setPassword('hashed_password');

        $this->userRepository->expects($this->once())
            ->method('existsByEmail')
            ->with('test@example.com')
            ->willReturn(false);

        $this->userPasswordHasher->expects($this->once())
            ->method('hashPassword')
            ->with($expectedHasherUser, 'testtest')
            ->willReturn('hashed_password');

        $this->userRepository
            ->expects($this->once())
            ->method('saveAndCommit')
            ->with($hasherUser);

        $this->authenticationSuccessHandler->expects($this->once())
            ->method('handleAuthenticationSuccess')
            ->with($hasherUser)
            ->willReturn($response);

        $signUpRequest = (new SignUpRequest())
            ->setEmail('test@example.com')
            ->setPassword('testtest')
            ->setLastName('test')
            ->setFirstName('test')
            ->setConfirmPassword('testtest');

        $this->assertEquals($response, $this->createService()->signUp($signUpRequest));

    }
}

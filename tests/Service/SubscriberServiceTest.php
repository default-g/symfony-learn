<?php

namespace App\Tests\Service;

use App\Entity\Subscriber;
use App\Exception\SubscriberAlreadyExistsException;
use App\Model\SubscriberRequest;
use App\Repository\SubscriberRepository;
use App\Service\SubscriberService;
use App\Tests\AbstractTestCase;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class SubscriberServiceTest extends AbstractTestCase
{
    private SubscriberRepository $subscriberRepository;

    private EntityManager $entityManager;

    private const EMAIL = 'test@example.com';

    protected function setUp(): void
    {
        parent::setUp();

        $this->subscriberRepository = $this->createMock(SubscriberRepository::class);
        $this->entityManager = $this->createMock(EntityManager::class);
    }


    public function testSubscribeEmailAlreadyExists(): void
    {
        $this->expectException(SubscriberAlreadyExistsException::class);

        $this->subscriberRepository->expects($this->once())
            ->method('existsByEmail')
            ->with(self::EMAIL)
            ->willReturn(true);

        $request = new SubscriberRequest();
        $request->setEmail(self::EMAIL);
        $request->setAgreed(true);

        (new SubscriberService($this->subscriberRepository, $this->entityManager))->subscribe($request);

    }


    public function testSubscribeSuccessful(): void
    {
        $this->subscriberRepository->expects($this->once())
            ->method('existsByEmail')
            ->with(self::EMAIL)
            ->willReturn(false);

        $subscriber = new Subscriber();
        $subscriber->setEmail(self::EMAIL);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($subscriber);

        $request = new SubscriberRequest();
        $request->setEmail(self::EMAIL);
        $request->setAgreed(true);

        (new SubscriberService($this->subscriberRepository, $this->entityManager))->subscribe($request);

    }
}

<?php

namespace App\Service;

use App\Entity\Subscriber;
use App\Exception\SubscriberAlreadyExistsException;
use App\Model\SubscriberRequest;
use App\Repository\SubscriberRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class SubscriberService
{
    public function __construct(
        private readonly SubscriberRepository $subscriberRepository,
    ) {}

    public function subscribe(SubscriberRequest $subscriberRequest): void
    {
        if ($this->subscriberRepository->existsByEmail($subscriberRequest->getEmail())) {
            throw new SubscriberAlreadyExistsException('Email already exists');
        }

        $subscriber = new Subscriber();
        $subscriber->setEmail($subscriberRequest->getEmail());

        $this->subscriberRepository->saveAndCommit($subscriber);
    }
}

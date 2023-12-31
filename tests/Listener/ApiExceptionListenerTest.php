<?php

namespace App\Tests\Listener;

use App\Listener\ApiExceptionListener;
use App\Model\ErrorResponse;
use App\Service\ExceptionHandler\ExceptionMapping;
use App\Service\ExceptionHandler\ExceptionMappingResolver;
use App\Tests\AbstractTestCase;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class ApiExceptionListenerTest extends AbstractTestCase
{
    private ExceptionMappingResolver $exceptionMappingResolver;
    private LoggerInterface $logger;

    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = $this->createMock(ExceptionMappingResolver::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);

    }

    public function testNon500MappingWithHiddenMessage()
    {
        $mapping = ExceptionMapping::fromCode(Response::HTTP_NOT_FOUND);
        $responseMessage = Response::$statusTexts[$mapping->getCode()];
        $responseBody = json_encode(['error' => $responseMessage]);

        $this->resolver->expects($this->once())
            ->method('resolve')
            ->with(\InvalidArgumentException::class)
            ->willReturn($mapping);

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with(new ErrorResponse($responseMessage), JsonEncoder::FORMAT)
            ->willReturn($responseBody);

        $event = $this->createExceptionEvent(new InvalidArgumentException("Not Found"));

        $this->runListener($event);

        $this->assertResponse(Response::HTTP_NOT_FOUND, $responseBody, $event->getResponse());

    }


    public function testNon500MappingWithPublicMessage()
    {
        $mapping = new ExceptionMapping(Response::HTTP_NOT_FOUND, false, false);
        $responseMessage = 'test exception';
        $responseBody = json_encode(['error' => $responseMessage]);

        $this->resolver->expects($this->once())
            ->method('resolve')
            ->with(\InvalidArgumentException::class)
            ->willReturn($mapping);

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with(new ErrorResponse($responseMessage), JsonEncoder::FORMAT)
            ->willReturn($responseBody);

        $event = $this->createExceptionEvent(new InvalidArgumentException("test exception"));

        $this->runListener($event);

        $this->assertResponse(Response::HTTP_NOT_FOUND, $responseBody, $event->getResponse());
    }


    public function testNon500MappingTriggersLogger()
    {
        $mapping = new ExceptionMapping(Response::HTTP_NOT_FOUND, false, true);
        $responseMessage = 'test exception';
        $responseBody = json_encode(['error' => $responseMessage]);

        $this->resolver->expects($this->once())
            ->method('resolve')
            ->with(\InvalidArgumentException::class)
            ->willReturn($mapping);

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with(new ErrorResponse($responseMessage), JsonEncoder::FORMAT)
            ->willReturn($responseBody);

        $this->logger->expects($this->once())
            ->method('error');

        $event = $this->createExceptionEvent(new InvalidArgumentException("test exception"));

        $this->runListener($event);

        $this->assertResponse(Response::HTTP_NOT_FOUND, $responseBody, $event->getResponse());
    }


    public function test500Mapping()
    {
        $mapping = ExceptionMapping::fromCode(Response::HTTP_BAD_GATEWAY);
        $responseMessage = Response::$statusTexts[$mapping->getCode()];
        $responseBody = json_encode(['error' => $responseMessage]);

        $this->resolver->expects($this->once())
            ->method('resolve')
            ->with(\InvalidArgumentException::class)
            ->willReturn($mapping);

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with(new ErrorResponse($responseMessage), JsonEncoder::FORMAT)
            ->willReturn($responseBody);

        $this->logger->expects($this->once())
            ->method('error')
            ->with('Bad Gateway', $this->anything());

        $event = $this->createExceptionEvent(new InvalidArgumentException('Bad Gateway'));

        $this->runListener($event);

        $this->assertResponse(Response::HTTP_BAD_GATEWAY, $responseBody, $event->getResponse());
    }


    private function runListener(ExceptionEvent $event, bool $isDebug = false): void
    {
        (new ApiExceptionListener($this->resolver, $this->logger, $this->serializer, $isDebug))($event);
    }

}

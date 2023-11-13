<?php

namespace App\Tests\Service\ExceptionHandler;

use App\Service\ExceptionHandler\ExceptionMappingResolver;
use App\Tests\AbstractTestCase;
use InvalidArgumentException;

class ExceptionMappingResolverTest extends AbstractTestCase
{

    public function testResolveThrowsExceptionEmptyCode(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ExceptionMappingResolver(['someClass' => ['hidden' => 'true']]);
    }


    public function testResolveReturnNullWhenNotFound(): void
    {
        $resolver = new ExceptionMappingResolver(['someClass' => ['code' => 404]]);

        $this->assertNull($resolver->resolve(InvalidArgumentException::class));
    }


    public function testResolveClassItself(): void
    {
        $resolver = new ExceptionMappingResolver([InvalidArgumentException::class => ['code' => 400]]);
        $mapping = $resolver->resolve(InvalidArgumentException::class);

        $this->assertEquals(400, $mapping->getCode());
    }


    public function testResolveClassParent(): void
    {
        $resolver = new ExceptionMappingResolver([\LogicException::class => ['code' => 123]]);
        $mapping = $resolver->resolve(\InvalidArgumentException::class);

        $this->assertEquals(123, $mapping->getCode());
    }


    public function testResolveDefaultHidden(): void
    {
        $resolver = new ExceptionMappingResolver([\LogicException::class => ['code' => 123]]);
        $mapping = $resolver->resolve(\InvalidArgumentException::class);

        $this->assertEquals(true, $mapping->isHidden());
    }


    public function testResolveDefaultLoggable(): void
    {
        $resolver = new ExceptionMappingResolver([\LogicException::class => ['code' => 123]]);
        $mapping = $resolver->resolve(\InvalidArgumentException::class);

        $this->assertEquals(false, $mapping->isLoggable());
    }
}

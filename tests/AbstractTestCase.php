<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

abstract class AbstractTestCase extends TestCase
{
    protected function setEntityId(object $entity, int $value, string $idField = 'id'): void
    {
        $class = new ReflectionClass($entity);
        $property = $class->getProperty($idField);
        $property->setAccessible(true);
        $property->setValue($entity, $value);
        $property->setAccessible(false);
    }
}

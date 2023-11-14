<?php

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \RuntimeException
{
    public function __construct(private readonly ConstraintViolationListInterface $violationList)
    {
        parent::__construct("validation failed");
    }

    public function getViolationList(): ConstraintViolationListInterface
    {
        return $this->violationList;
    }


}

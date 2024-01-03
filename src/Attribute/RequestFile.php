<?php

namespace App\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class RequestFile
{
    public function __construct(
        private string $field,
        private array $constraints = []
    )
    {
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): RequestFile
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @return Constraint[]
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }

    /**
     * @param Constraint[]
     */
    public function setConstraints(array $constraints): RequestFile
    {
        $this->constraints = $constraints;
        return $this;
    }


}

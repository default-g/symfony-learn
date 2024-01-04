<?php

namespace App\Exception;

class BookCategoryNotEmptyException extends \RuntimeException
{
    public function __construct(int $bookCount)
    {
        parent::__construct("book category has {$bookCount} books");
    }

}

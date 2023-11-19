<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class BookNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Book not found');
    }
}

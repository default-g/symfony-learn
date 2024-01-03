<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

class PublishBookRequest
{
    #[NotBlank]
    private \DateTimeInterface $date;


    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }


    public function setDate(\DateTimeInterface $date): PublishBookRequest
    {
        $this->date = $date;
        return $this;
    }

}

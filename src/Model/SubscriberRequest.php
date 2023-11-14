<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;

class SubscriberRequest
{
    #[Email]
    #[NotBlank]
    private string $email;

    #[IsTrue]
    private bool $agreed;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }


    public function isAgreed(): bool
    {
        return $this->agreed;
    }


    public function setAgreed(bool $agreed): void
    {
        $this->agreed = $agreed;
    }


}

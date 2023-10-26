<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/')] // Add a path within the parentheses, like #[Route('/')]
    public function root(): Response
    {
        return $this->json(['test' => 'hello']);
    }
    
}

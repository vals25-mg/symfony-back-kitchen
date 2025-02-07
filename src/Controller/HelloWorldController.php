<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HelloWorldController extends AbstractController
{
    #[Route('/helloworld', name: 'app_helloworld', methods: ['GET'])]
    public function index(): Response
    {
        return new Response('Hello World!');
    }
}

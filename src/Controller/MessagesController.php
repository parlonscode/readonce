<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('messages/create.html.twig');
    }
}

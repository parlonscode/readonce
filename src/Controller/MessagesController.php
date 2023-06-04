<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessagesController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function create(): Response
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class)
            ->add('body', TextareaType::class)
            ->getForm()
        ;

        return $this->render('messages/create.html.twig', compact('form'));
    }
}

<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessagesController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class)
            ->add('body', TextareaType::class)
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO: Save message in database
            // TODO: Send email
            // TODO: Redirect back with success message
            
            dd('form handling');
        }

        return $this->render('messages/create.html.twig', compact('form'));
    }
}

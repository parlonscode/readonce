<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessagesController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function create(Request $request, MessageRepository $messageRepository): Response
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class)
            ->add('body', TextareaType::class)
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = new Message;
            $message->setEmail($form['email']->getData());
            $message->setBody($form['body']->getData());

            $messageRepository->save($message, flush: true);

            dd('done');
            // TODO: Send email
            // TODO: Redirect back with success message
        }

        return $this->render('messages/create.html.twig', compact('form'));
    }
}

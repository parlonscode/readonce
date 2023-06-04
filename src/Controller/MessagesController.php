<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessagesController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function create(Request $request, MessageRepository $messageRepository): Response
    {
        dd($messageRepository->find(8));
        $message = new Message;

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageRepository->save($message, flush: true);

            // TODO: Send email
            
            $this->addFlash('success', 'Message sent successfully.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('messages/create.html.twig', compact('form'));
    }

    #[Route(
        '/messages/{uuid}',
        requirements: [
            'uuid' => Requirement::UUID_V7
        ],
        name: 'app_messages_show',
        methods: ['GET']
    )]
    public function show(string $uuid, MessageRepository $messageRepository): Response
    {
        $message = $messageRepository->findOneBy([
            'deletedAt' => null,
            'uuid' => $uuid
        ]);

        if ($message) {
            $messageRepository->remove($message);
        }

        return $this->render('messages/show.html.twig', compact('message'));
    }
}

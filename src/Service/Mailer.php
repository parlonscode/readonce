<?php

namespace App\Service;

use App\Entity\Message;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function sendReadOnceMessage(Message $message): void
    {
        $email = (new TemplatedEmail)
            ->to($message->getEmail())
            ->subject('New read-once message!')
            ->htmlTemplate('emails/message.html.twig')
            ->context(compact('message'))
        ;
        $this->mailer->send($email);
    }
}

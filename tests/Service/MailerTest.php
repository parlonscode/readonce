<?php

namespace App\Tests\Service;

use App\Entity\Message;
use App\Service\Mailer;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerTest extends TestCase
{
    private $symfonyMailer;
    private $mailer;

    /** @test **/
    public function sendReadOnceMessage_should_work_properly(): void
    {
        $symfonyMailer = $this->createMock(MailerInterface::class);

        $symfonyMailer
            ->expects($this->once())
            ->method('send')
        ;

        $mailer = new Mailer($symfonyMailer);

        $message = new Message;
        $message->setEmail('johndoe@example.com');
        $message->setBody('Hello from the other side!');
        $mailer->sendReadOnceMessage($message);
    }
}

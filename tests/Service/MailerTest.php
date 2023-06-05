<?php

namespace App\Tests\Service;

use App\Entity\Message;
use App\Service\Mailer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerTest extends TestCase
{
    /** @test **/
    public function sendReadOnceMessage_should_work_properly(): void
    {
        $symfonyMailer = $this->createMock(MailerInterface::class);

        $symfonyMailer
            ->expects($this->once())
            ->method('send')
        ;

        $message = new Message;
        $message->setEmail('johndoe@example.com');
        $message->setBody('Hello from the other side!');
        
        $mailer = new Mailer($symfonyMailer);
        $email = $mailer->sendReadOnceMessage($message);
        $this->assertSame('New read-once message!', $email->getSubject());
        $this->assertCount(1, $email->getTo());
        $addresses = $email->getTo();
        $this->assertInstanceOf(Address::class, $addresses[0]);
        $this->assertSame('', $addresses[0]->getName());
        $this->assertSame('johndoe@example.com', $addresses[0]->getAddress());
    }
}

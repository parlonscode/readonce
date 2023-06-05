<?php

namespace App\Tests\Entity;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MessageTest extends KernelTestCase
{
    private $messageRepository;

    public function setUp(): void
    {
        static::bootKernel();

        $this->messageRepository = $this->getContainer()->get(MessageRepository::class);
    }

    /** @test */
    public function messages_can_be_created()
    {
        $this->assertSame(0, $this->messageRepository->count([]));

        $message = new Message;
        $message->setEmail('johndoe@example.com');
        $message->setBody('Hello from the other side!');
        $this->messageRepository->save($message, flush: true);

        $this->assertSame(1, $this->messageRepository->count([]));
        $message = $this->messageRepository->findOneByEmail('johndoe@example.com');
        $this->assertSame('Hello from the other side!', $message->getBody());
        $this->assertNotNull($message->getCreatedAt());
        $this->assertNotNull($message->getUpdatedAt());
        $this->assertNull($message->getDeletedAt());
    }

    /** @test */
    public function messages_are_soft_deleted()
    {
        $message = new Message;
        $message->setEmail('johndoe@example.com');
        $message->setBody('Hello from the other side!');
        $this->messageRepository->save($message, flush: true);
        $this->assertSame(1, $this->messageRepository->count([]));
        
        $message = $this->messageRepository->findOneByEmail('johndoe@example.com');
        $this->assertNull($message->getDeletedAt());

        $this->messageRepository->remove($message, flush: true);
        $this->assertNull($this->messageRepository->findOneByEmail('johndoe@example.com'));
        $this->assertSame(0, $this->messageRepository->count([]));

        $em = $this->getContainer()->get(EntityManagerInterface::class);
        $em->getFilters()->disable('softdeleteable');

        $this->assertSame(1, $this->messageRepository->count([]));
        $message = $this->messageRepository->findOneByEmail('johndoe@example.com');
        $this->assertNotNull($message->getDeletedAt());
    }
}

<?php

namespace App\EntityListener;

use App\Entity\Message;
use Doctrine\ORM\Events;
use Symfony\Component\Uid\Factory\UuidFactory;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(event: Events::prePersist, entity: Message::class)]
class MessageEntityListener
{
    public function __construct(private readonly UuidFactory $uuidFactory)
    {
    }

    public function prePersist(Message $message, LifecycleEventArgs $event): void
    {
        $message->setUuid($this->uuidFactory->create());
    }
}

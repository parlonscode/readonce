<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\PreRemoveEventArgs;

trait SoftDeleteable
{
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    #[ORM\PreRemove]
    public function setDeletedAtValue(PreRemoveEventArgs $eventArgs): void
    {
        $this->setDeletedAt(new \DateTimeImmutable);
        
        $em = $eventArgs->getObjectManager();
        $em->persist($this);
        $em->flush();
    }
}

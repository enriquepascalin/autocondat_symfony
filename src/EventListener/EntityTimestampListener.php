<?php

declare(strict_types=1);

namespace App\EventListener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Contracts\TimestampableInterface;

class EntityTimestampListener
{
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof TimestampableInterface  && null === $entity->getCreatedAt()) {
            $entity->setCreatedAt(new \DateTimeImmutable());
            $entity->setUpdatedAt(new \DateTimeImmutable());
        }
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof TimestampableInterface) {
            $entity->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}

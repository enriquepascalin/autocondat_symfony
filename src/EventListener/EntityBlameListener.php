<?php

declare(strict_types=1);

namespace App\EventListener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use App\Contracts\BlameableInterface;
use App\Entity\AuthenticationModule\User;

class EntityBlameListener
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        $user = $this->security->getUser();

        if ($entity instanceof BlameableInterface && $user instanceof User) {
            if (null === $entity->getCreatedBy()) {
                $entity->setCreatedBy($user);
                $entity->setUpdatedBy($user);
            }

        }
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        $user = $this->security->getUser();

        if ($entity instanceof BlameableInterface && $user instanceof User) {
            $entity->setUpdatedBy($user);
        }
    }
}

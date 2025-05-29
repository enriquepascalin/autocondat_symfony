<?php

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use App\Contracts\BlameableInterface;
use App\Entity\AuthenticationModule\User;

class EntityBlameListener
{
    public function __construct(
        private Security $security
    )
    {}

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $user = $this->security->getUser();

        if ($entity instanceof BlameableInterface && $user instanceof User) {
            if ($entity->getCreatedBy() === null) {
                $entity->setCreatedBy($user);
                $entity->setUpdatedBy($user);
            }
            
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $user = $this->security->getUser();

        if ($entity instanceof BlameableInterface && $user instanceof User) {
            $entity->setUpdatedBy($user);
        }
    }
}
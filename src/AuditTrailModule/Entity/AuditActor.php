<?php
/**
 * Copyright © 2025 Enrique Pascalin <erparom@gmail.com>
 * This source code is protected under international copyright law.
 * All rights reserved. No warranty, explicit or implicit, provided.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * This file is confidential and only available to authorized individuals with the
 * permission of the copyright holders.  If you encounter this file and do not have
 * permission, please contact the copyright holders and delete this file.
 *
 * @author Enrique Pascalin, Erparom Technologies
 *
 * @version 1.0.0
 *
 * @since 2025-06-01
 *
 * @license license.md
 */

declare(strict_types=1);

namespace App\AuditTrailModule\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\AuditTrailModule\Repository\AuditActorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: AuditActorRepository::class)]
#[ApiResource]
#[Broadcast]
class AuditActor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, AuditExport>
     */
    #[ORM\OneToMany(targetEntity: AuditExport::class, mappedBy: 'requestedBy')]
    private Collection $auditExports;

    /**
     * @var Collection<int, AuditEvent>
     */
    #[ORM\OneToMany(targetEntity: AuditEvent::class, mappedBy: 'actor')]
    private Collection $events;

    public function __construct()
    {
        $this->auditExports = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, AuditExport>
     */
    public function getAuditExports(): Collection
    {
        return $this->auditExports;
    }

    public function addAuditExport(AuditExport $auditExport): static
    {
        if (!$this->auditExports->contains($auditExport)) {
            $this->auditExports->add($auditExport);
            $auditExport->setRequestedBy($this);
        }

        return $this;
    }

    public function removeAuditExport(AuditExport $auditExport): static
    {
        if ($this->auditExports->removeElement($auditExport)) {
            // set the owning side to null (unless already changed)
            if ($auditExport->getRequestedBy() === $this) {
                $auditExport->setRequestedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AuditEvent>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(AuditEvent $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setActor($this);
        }

        return $this;
    }

    public function removeEvent(AuditEvent $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getActor() === $this) {
                $event->setActor(null);
            }
        }

        return $this;
    }
}

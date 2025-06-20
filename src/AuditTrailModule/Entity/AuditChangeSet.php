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
use App\AuditTrailModule\Repository\AuditChangeSetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: AuditChangeSetRepository::class)]
#[ApiResource]
#[Broadcast]
class AuditChangeSet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'auditChangeSets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AuditEvent $event = null;

    #[ORM\Column(length: 255)]
    private ?string $field = null;

    #[ORM\Column(nullable: true)]
    private ?array $oldValue = null;

    #[ORM\Column(nullable: true)]
    private ?array $newValue = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?AuditEvent
    {
        return $this->event;
    }

    public function setEvent(?AuditEvent $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function setField(string $field): static
    {
        $this->field = $field;

        return $this;
    }

    public function getOldValue(): ?array
    {
        return $this->oldValue;
    }

    public function setOldValue(?array $oldValue): static
    {
        $this->oldValue = $oldValue;

        return $this;
    }

    public function getNewValue(): ?array
    {
        return $this->newValue;
    }

    public function setNewValue(?array $newValue): static
    {
        $this->newValue = $newValue;

        return $this;
    }
}

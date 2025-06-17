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

namespace App\Entity\MultitenancyModule;

use App\Entity\AuthenticationModule\User;
use App\Repository\MultitenancyModule\TenantAuditLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TenantAuditLogRepository::class)]
class TenantAuditLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'auditLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tenant $tenant = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $changedBy = null;

    #[ORM\Column(length: 100)]
    private ?string $changedField = null;

    #[ORM\Column(nullable: true)]
    private ?array $oldValue = null;

    #[ORM\Column(nullable: true)]
    private ?array $newValue = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $timestamp = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function setTenant(?Tenant $tenant): static
    {
        $this->tenant = $tenant;

        return $this;
    }

    public function getChangedBy(): ?User
    {
        return $this->changedBy;
    }

    public function setChangedBy(?User $changedBy): static
    {
        $this->changedBy = $changedBy;

        return $this;
    }

    public function getChangedField(): ?string
    {
        return $this->changedField;
    }

    public function setChangedField(string $changedField): static
    {
        $this->changedField = $changedField;

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

    public function getTimestamp(): ?\DateTimeImmutable
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeImmutable $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}

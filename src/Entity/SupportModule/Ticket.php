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

namespace App\Entity\SupportModule;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\AuthenticationModule\User;
use App\Repository\SupportModule\TicketRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ApiResource]
#[Broadcast]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\ManyToOne]
    private ?User $agent = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $reportedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $solvedAt = null;

    #[ORM\Column(enumType: IncidenceTypeEnum::class)]
    private ?IncidenceTypeEnum $type = null;

    #[ORM\Column(enumType: SeverityEnum::class)]
    private ?SeverityEnum $severity = null;

    #[ORM\Column(enumType: TicketStatusEnum::class)]
    private ?TicketStatusEnum $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getAgent(): ?User
    {
        return $this->agent;
    }

    public function setAgent(?User $agent): static
    {
        $this->agent = $agent;

        return $this;
    }

    public function getReportedAt(): ?\DateTimeImmutable
    {
        return $this->reportedAt;
    }

    public function setReportedAt(\DateTimeImmutable $reportedAt): static
    {
        $this->reportedAt = $reportedAt;

        return $this;
    }

    public function getSolvedAt(): ?\DateTimeImmutable
    {
        return $this->solvedAt;
    }

    public function setSolvedAt(\DateTimeImmutable $solvedAt): static
    {
        $this->solvedAt = $solvedAt;

        return $this;
    }

    public function getType(): ?IncidenceTypeEnum
    {
        return $this->type;
    }

    public function setType(IncidenceTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSeverity(): ?SeverityEnum
    {
        return $this->severity;
    }

    public function setSeverity(SeverityEnum $severity): static
    {
        $this->severity = $severity;

        return $this;
    }

    public function getStatus(): ?TicketStatusEnum
    {
        return $this->status;
    }

    public function setStatus(TicketStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}

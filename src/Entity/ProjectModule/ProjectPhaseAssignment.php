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

namespace App\Entity\ProjectModule;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\AuthenticationModule\User;
use App\Entity\MultitenancyModule\Tenant;
use App\Repository\ProjectModule\ProjectPhaseAssignmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ProjectPhaseAssignmentRepository::class)]
#[ApiResource]
#[Broadcast]
class ProjectPhaseAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'projectPhaseAssignments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tenant $tenant = null;

    #[ORM\ManyToOne(inversedBy: 'projectPhaseAssignments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProjectPhase $phase = null;

    #[ORM\ManyToOne(inversedBy: 'projectPhaseAssignments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $autocondatUser = null;

    #[ORM\Column(enumType: AssignmentRoleEnum::class)]
    private ?AssignmentRoleEnum $role = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $startDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $endDate = null;

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

    public function getPhase(): ?ProjectPhase
    {
        return $this->phase;
    }

    public function setPhase(?ProjectPhase $phase): static
    {
        $this->phase = $phase;

        return $this;
    }

    public function getAutocondatUser(): ?User
    {
        return $this->autocondatUser;
    }

    public function setAutocondatUser(?User $autocondatUser): static
    {
        $this->autocondatUser = $autocondatUser;

        return $this;
    }

    public function getRole(): ?AssignmentRoleEnum
    {
        return $this->role;
    }

    public function setRole(AssignmentRoleEnum $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }
}

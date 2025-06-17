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

namespace App\Entity\NotificationModule;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\AuthenticationModule\User;
use App\Entity\MultitenancyModule\Tenant;
use App\Repository\NotificationModule\AcknowledgementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: AcknowledgementRepository::class)]
#[ApiResource]
#[Broadcast]
class Acknowledgement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'acknowledgements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tenant $tenant = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $autocondatUser = null;

    #[ORM\ManyToOne(inversedBy: 'acknowledgements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Notification $notification = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $acknowledgedAt = null;

    #[ORM\Column(enumType: AckActionEnum::class)]
    private ?AckActionEnum $action = null;

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

    public function getAutocondatUser(): ?User
    {
        return $this->autocondatUser;
    }

    public function setAutocondatUser(?User $autocondatUser): static
    {
        $this->autocondatUser = $autocondatUser;

        return $this;
    }

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    public function setNotification(?Notification $notification): static
    {
        $this->notification = $notification;

        return $this;
    }

    public function getAcknowledgedAt(): ?\DateTimeImmutable
    {
        return $this->acknowledgedAt;
    }

    public function setAcknowledgedAt(\DateTimeImmutable $acknowledgedAt): static
    {
        $this->acknowledgedAt = $acknowledgedAt;

        return $this;
    }

    public function getAction(): ?AckActionEnum
    {
        return $this->action;
    }

    public function setAction(AckActionEnum $action): static
    {
        $this->action = $action;

        return $this;
    }
}

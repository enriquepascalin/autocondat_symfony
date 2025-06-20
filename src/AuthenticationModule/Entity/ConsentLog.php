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

namespace App\AuthenticationModule\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\AuthenticationModule\Repository\ConsentLogRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ConsentLogRepository::class)]
#[ApiResource]
#[Broadcast]
class ConsentLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'consentLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $autocondatUser = null;

    #[ORM\Column(length: 50)]
    private ?string $consentType = null;

    #[ORM\Column]
    private ?bool $consentGiven = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $timestamp = null;

    #[ORM\Column(length: 20)]
    private ?string $version = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $ipAddress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userAgent = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getConsentType(): ?string
    {
        return $this->consentType;
    }

    public function setConsentType(string $consentType): static
    {
        $this->consentType = $consentType;

        return $this;
    }

    public function isConsentGiven(): ?bool
    {
        return $this->consentGiven;
    }

    public function setConsentGiven(bool $consentGiven): static
    {
        $this->consentGiven = $consentGiven;

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

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(?string $ipAddress): static
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): static
    {
        $this->userAgent = $userAgent;

        return $this;
    }
}

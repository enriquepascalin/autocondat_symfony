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
use App\AuthenticationModule\Repository\SessionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
#[ApiResource]
#[Broadcast]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'sessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $autocondatUser = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\Column]
    private ?\DateTime $expiresAt = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $lastUsed = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $ipAdress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userAgent = null;

    #[ORM\Column]
    private ?bool $isRevoked = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userAgentHash = null;

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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        /** @ToDo Encode Hash token */
        if (strlen($token) > 255) {
            throw new \InvalidArgumentException('Token length exceeds maximum allowed length of 255 characters.');
        }
        if (empty($token)) {
            throw new \InvalidArgumentException('Token cannot be empty.');
        }
        if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $token)) {
            throw new \InvalidArgumentException('Token contains invalid characters. Only alphanumeric characters, hyphens, and underscores are allowed.');
        }
        if (strlen($token) < 10) {
            throw new \InvalidArgumentException('Token length must be at least 10 characters.');
        }
        if (strlen($token) > 255) {
            throw new \InvalidArgumentException('Token length exceeds maximum allowed length of 255 characters.');
        }
        if (!ctype_alnum(str_replace(['-', '_'], '', $token))) {
            throw new \InvalidArgumentException('Token must contain only alphanumeric characters, hyphens, and underscores.');
        }
        if (preg_match('/\s/', $token)) {
            throw new \InvalidArgumentException('Token cannot contain whitespace characters.');
        }
        if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $token)) {
            throw new \InvalidArgumentException('Token must contain only alphanumeric characters, hyphens, and underscores.');
        }
        if (strlen($token) < 10) {
            throw new \InvalidArgumentException('Token length must be at least 10 characters.');
        }
        $this->token = $token;

        return $this;
    }

    public function getExpiresAt(): ?\DateTime
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTime $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLastUsed(): ?\DateTime
    {
        return $this->lastUsed;
    }

    public function setLastUsed(?\DateTime $lastUsed): static
    {
        $this->lastUsed = $lastUsed;

        return $this;
    }

    public function getIpAdress(): ?string
    {
        return $this->ipAdress;
    }

    public function setIpAdress(?string $ipAdress): static
    {
        $this->ipAdress = $ipAdress;

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

    public function isRevoked(): ?bool
    {
        return $this->isRevoked;
    }

    public function setIsRevoked(bool $isRevoked): static
    {
        $this->isRevoked = $isRevoked;

        return $this;
    }

    public function getUserAgentHash(): ?string
    {
        return $this->userAgentHash;
    }

    public function setUserAgentHash(?string $userAgentHash): static
    {
        $this->userAgentHash = $userAgentHash;

        return $this;
    }
}

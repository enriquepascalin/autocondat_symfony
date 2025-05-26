<?php

namespace App\Entity\AuthenticationModule;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AuthenticationModule\SessionRepository;
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

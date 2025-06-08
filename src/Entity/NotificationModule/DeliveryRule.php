<?php

declare(strict_types=1);

namespace App\Entity\NotificationModule;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\MultitenancyModule\Segment;
use App\Entity\MultitenancyModule\Tenant;
use App\Repository\NotificationModule\DeliveryRuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: DeliveryRuleRepository::class)]
#[ApiResource]
#[Broadcast]
class DeliveryRule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'deliveryRules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tenant $tenant = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Segment>
     */
    #[ORM\ManyToMany(targetEntity: Segment::class, inversedBy: 'deliveryRules')]
    private Collection $segments;

    #[ORM\ManyToOne(inversedBy: 'deliveryRules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Notification $notification = null;

    /**
     * @var Collection<int, Channel>
     */
    #[ORM\ManyToMany(targetEntity: Channel::class, inversedBy: 'deliveryRules')]
    private Collection $channels;

    #[ORM\Column(nullable: true)]
    private ?array $retryPolicy = null;

    #[ORM\Column]
    private ?bool $requireAcknowledgment = null;

    #[ORM\Column]
    private ?int $maxAckAttempts = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $expiresAt = null;

    public function __construct()
    {
        $this->segments = new ArrayCollection();
        $this->channels = new ArrayCollection();
    }

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Segment>
     */
    public function getSegments(): Collection
    {
        return $this->segments;
    }

    public function addSegment(Segment $segment): static
    {
        if (!$this->segments->contains($segment)) {
            $this->segments->add($segment);
        }

        return $this;
    }

    public function removeSegment(Segment $segment): static
    {
        $this->segments->removeElement($segment);

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

    /**
     * @return Collection<int, Channel>
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function addChannel(Channel $channel): static
    {
        if (!$this->channels->contains($channel)) {
            $this->channels->add($channel);
        }

        return $this;
    }

    public function removeChannel(Channel $channel): static
    {
        $this->channels->removeElement($channel);

        return $this;
    }

    public function getRetryPolicy(): ?array
    {
        return $this->retryPolicy;
    }

    public function setRetryPolicy(?array $retryPolicy): static
    {
        $this->retryPolicy = $retryPolicy;

        return $this;
    }

    public function isRequireAcknowledgment(): ?bool
    {
        return $this->requireAcknowledgment;
    }

    public function setRequireAcknowledgment(bool $requireAcknowledgment): static
    {
        $this->requireAcknowledgment = $requireAcknowledgment;

        return $this;
    }

    public function getMaxAckAttempts(): ?int
    {
        return $this->maxAckAttempts;
    }

    public function setMaxAckAttempts(int $maxAckAttempts): static
    {
        $this->maxAckAttempts = $maxAckAttempts;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }
}

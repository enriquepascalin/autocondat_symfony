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

use ApiPlatform\Metadata\ApiResource;
use App\Entity\AuthenticationModule\User;
use App\Entity\NotificationModule\Audience;
use App\Entity\NotificationModule\DeliveryRule;
use App\Repository\MultitenancyModule\SegmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: SegmentRepository::class)]
#[ApiResource]
#[Broadcast]
class Segment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    /**
     * @var Collection<int, Tenant>
     */
    #[ORM\ManyToMany(targetEntity: Tenant::class, mappedBy: 'segments')]
    private Collection $tenants;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'segments')]
    private Collection $users;

    /**
     * @var Collection<int, Audience>
     */
    #[ORM\ManyToMany(targetEntity: Audience::class, mappedBy: 'segments')]
    private Collection $audiences;

    /**
     * @var Collection<int, DeliveryRule>
     */
    #[ORM\ManyToMany(targetEntity: DeliveryRule::class, mappedBy: 'segments')]
    private Collection $deliveryRules;

    public function __construct()
    {
        $this->tenants = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->audiences = new ArrayCollection();
        $this->deliveryRules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Tenant>
     */
    public function getTenants(): Collection
    {
        return $this->tenants;
    }

    public function addTenant(Tenant $tenant): static
    {
        if (!$this->tenants->contains($tenant)) {
            $this->tenants->add($tenant);
            $tenant->addSegment($this);
        }

        return $this;
    }

    public function removeTenant(Tenant $tenant): static
    {
        if ($this->tenants->removeElement($tenant)) {
            $tenant->removeSegment($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addSegment($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeSegment($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Audience>
     */
    public function getAudiences(): Collection
    {
        return $this->audiences;
    }

    public function addAudience(Audience $audience): static
    {
        if (!$this->audiences->contains($audience)) {
            $this->audiences->add($audience);
            $audience->addSegment($this);
        }

        return $this;
    }

    public function removeAudience(Audience $audience): static
    {
        if ($this->audiences->removeElement($audience)) {
            $audience->removeSegment($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, DeliveryRule>
     */
    public function getDeliveryRules(): Collection
    {
        return $this->deliveryRules;
    }

    public function addDeliveryRule(DeliveryRule $deliveryRule): static
    {
        if (!$this->deliveryRules->contains($deliveryRule)) {
            $this->deliveryRules->add($deliveryRule);
            $deliveryRule->addSegment($this);
        }

        return $this;
    }

    public function removeDeliveryRule(DeliveryRule $deliveryRule): static
    {
        if ($this->deliveryRules->removeElement($deliveryRule)) {
            $deliveryRule->removeSegment($this);
        }

        return $this;
    }
}

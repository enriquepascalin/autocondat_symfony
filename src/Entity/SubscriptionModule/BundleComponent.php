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

namespace App\Entity\SubscriptionModule;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SubscriptionModule\BundleComponentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use App\Traits\BlameableTrait;
use App\Traits\TimestampableTrait;
use App\Traits\SoftDeletableTrait;
use App\Traits\TenantAwareTrait;

#[ORM\Entity(repositoryClass: BundleComponentRepository::class)]
#[ApiResource]
#[Broadcast]
class BundleComponent
{
    use TimestampableTrait;
    use SoftDeletableTrait;
    use TenantAwareTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bundleComponents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bundle $bundle = null;

    #[ORM\ManyToOne]
    private ?Feature $feature = null;

    #[ORM\ManyToOne]
    private ?Service $service = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantity = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $accessTier = null;

    #[ORM\Column(nullable: true)]
    private ?array $overrides = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBundle(): ?Bundle
    {
        return $this->bundle;
    }

    public function setBundle(?Bundle $bundle): static
    {
        $this->bundle = $bundle;

        return $this;
    }

    public function getFeature(): ?Feature
    {
        return $this->feature;
    }

    public function setFeature(?Feature $feature): static
    {
        $this->feature = $feature;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getAccessTier(): ?string
    {
        return $this->accessTier;
    }

    public function setAccessTier(?string $accessTier): static
    {
        $this->accessTier = $accessTier;

        return $this;
    }

    public function getOverrides(): ?array
    {
        return $this->overrides;
    }

    public function setOverrides(?array $overrides): static
    {
        $this->overrides = $overrides;

        return $this;
    }
}

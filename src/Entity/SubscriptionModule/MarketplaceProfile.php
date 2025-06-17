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
use App\Repository\SubscriptionModule\MarketplaceProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use App\Traits\BlameableTrait;
use App\Traits\TimestampableTrait;
use App\Traits\SoftDeletableTrait;
use App\Traits\TenantAwareTrait;

#[ORM\Entity(repositoryClass: MarketplaceProfileRepository::class)]
#[ApiResource]
#[Broadcast]
class MarketplaceProfile
{
    use TimestampableTrait;
    use SoftDeletableTrait;
    use TenantAwareTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $displayName = null;

    #[ORM\Column(enumType: ProfileStatusEnum::class)]
    private ?ProfileStatusEnum $status = null;

    #[ORM\Column(nullable: true)]
    private ?array $paymentDetails = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getStatus(): ?ProfileStatusEnum
    {
        return $this->status;
    }

    public function setStatus(ProfileStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPaymentDetails(): ?array
    {
        return $this->paymentDetails;
    }

    public function setPaymentDetails(?array $paymentDetails): static
    {
        $this->paymentDetails = $paymentDetails;

        return $this;
    }
}

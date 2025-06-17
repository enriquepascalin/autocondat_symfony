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
use App\Repository\SubscriptionModule\SettlementLedgerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: SettlementLedgerRepository::class)]
#[ApiResource]
#[Broadcast]
class SettlementLedger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'settlementLedgers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MarketplaceTransaction $transaction = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(length: 3)]
    private ?string $currency = null;

    #[ORM\Column(enumType: SettlementStatusEnum::class)]
    private ?SettlementStatusEnum $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $settledAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransaction(): ?MarketplaceTransaction
    {
        return $this->transaction;
    }

    public function setTransaction(?MarketplaceTransaction $transaction): static
    {
        $this->transaction = $transaction;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getStatus(): ?SettlementStatusEnum
    {
        return $this->status;
    }

    public function setStatus(SettlementStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSettledAt(): ?\DateTimeImmutable
    {
        return $this->settledAt;
    }

    public function setSettledAt(\DateTimeImmutable $settledAt): static
    {
        $this->settledAt = $settledAt;

        return $this;
    }
}

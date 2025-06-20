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

namespace App\SubscriptionModule\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\MultitenancyModule\Entity\Tenant;
use App\SubscriptionModule\Repository\MarketplaceTransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use App\Traits\BlameableTrait;
use App\Traits\TimestampableTrait;
use App\Traits\SoftDeletableTrait;
use App\Traits\TenantAwareTrait;

#[ORM\Entity(repositoryClass: MarketplaceTransactionRepository::class)]
#[ApiResource]
#[Broadcast]
class MarketplaceTransaction
{
    use TimestampableTrait;
    use SoftDeletableTrait;
    use TenantAwareTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subscription $subscription = null;

    #[ORM\ManyToOne(inversedBy: 'marketplaceTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tenant $subscriptor = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(length: 3)]
    private ?string $currency = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $platformFee = null;

    #[ORM\Column(enumType: TransactionTypeEnum::class)]
    private ?TransactionTypeEnum $type = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    /**
     * @var Collection<int, SettlementLedger>
     */
    #[ORM\OneToMany(targetEntity: SettlementLedger::class, mappedBy: 'transaction', orphanRemoval: true)]
    private Collection $settlementLedgers;

    public function __construct()
    {
        $this->settlementLedgers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(?Subscription $subscription): static
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function getSubscriptor(): ?Tenant
    {
        return $this->subscriptor;
    }

    public function setSubscriptor(?Tenant $subscriptor): static
    {
        $this->subscriptor = $subscriptor;

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

    public function getPlatformFee(): ?string
    {
        return $this->platformFee;
    }

    public function setPlatformFee(?string $platformFee): static
    {
        $this->platformFee = $platformFee;

        return $this;
    }

    public function getType(): ?TransactionTypeEnum
    {
        return $this->type;
    }

    public function setType(TransactionTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, SettlementLedger>
     */
    public function getSettlementLedgers(): Collection
    {
        return $this->settlementLedgers;
    }

    public function addSettlementLedger(SettlementLedger $settlementLedger): static
    {
        if (!$this->settlementLedgers->contains($settlementLedger)) {
            $this->settlementLedgers->add($settlementLedger);
            $settlementLedger->setTransaction($this);
        }

        return $this;
    }

    public function removeSettlementLedger(SettlementLedger $settlementLedger): static
    {
        if ($this->settlementLedgers->removeElement($settlementLedger)) {
            // set the owning side to null (unless already changed)
            if ($settlementLedger->getTransaction() === $this) {
                $settlementLedger->setTransaction(null);
            }
        }

        return $this;
    }
}

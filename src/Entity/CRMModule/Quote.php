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

namespace App\Entity\CRMModule;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CRMModule\QuoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: QuoteRepository::class)]
#[ApiResource]
#[Broadcast]
class Quote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'quotes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $quoteNumber = null;

    #[ORM\Column(enumType: QuoteStatusEnum::class)]
    private ?QuoteStatusEnum $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $validUntil = null;

    #[ORM\Column]
    private ?float $totalAmount = null;

    /**
     * @var Collection<int, QuoteItem>
     */
    #[ORM\OneToMany(targetEntity: QuoteItem::class, mappedBy: 'quote', orphanRemoval: true)]
    private Collection $items;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'quote')]
    private Collection $orders;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getQuoteNumber(): ?string
    {
        return $this->quoteNumber;
    }

    public function setQuoteNumber(?string $quoteNumber): static
    {
        $this->quoteNumber = $quoteNumber;

        return $this;
    }

    public function getStatus(): ?QuoteStatusEnum
    {
        return $this->status;
    }

    public function setStatus(QuoteStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getValidUntil(): ?\DateTime
    {
        return $this->validUntil;
    }

    public function setValidUntil(?\DateTime $validUntil): static
    {
        $this->validUntil = $validUntil;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * @return Collection<int, QuoteItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(QuoteItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setQuote($this);
        }

        return $this;
    }

    public function removeItem(QuoteItem $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getQuote() === $this) {
                $item->setQuote(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setQuote($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getQuote() === $this) {
                $order->setQuote(null);
            }
        }

        return $this;
    }
}

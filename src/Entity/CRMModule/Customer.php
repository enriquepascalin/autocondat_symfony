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
use App\Repository\CRMModule\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use App\Contracts\BlameableInterface;
use App\Contracts\TimestampableInterface;
use App\Contracts\SoftDeletableInterface;
use App\Contracts\TenantAwareInterface;
use App\Traits\BlameableTrait;
use App\Traits\TimestampableTrait;
use App\Traits\SoftDeletableTrait;
use App\Traits\TenantAwareTrait;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ApiResource]
#[Broadcast]
class Customer
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
    private ?string $legalName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tradingName = null;

    #[ORM\Column(enumType: CustomerTypeEnum::class)]
    private ?CustomerTypeEnum $customerType = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $defaultCurrency = null;

    /**
     * @var Collection<int, Contact>
     */
    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $contacts;

    /**
     * @var Collection<int, Quote>
     */
    #[ORM\OneToMany(targetEntity: Quote::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $quotes;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $orders;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->quotes = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLegalName(): ?string
    {
        return $this->legalName;
    }

    public function setLegalName(string $legalName): static
    {
        $this->legalName = $legalName;

        return $this;
    }

    public function getTradingName(): ?string
    {
        return $this->tradingName;
    }

    public function setTradingName(?string $tradingName): static
    {
        $this->tradingName = $tradingName;

        return $this;
    }

    public function getCustomerType(): ?CustomerTypeEnum
    {
        return $this->customerType;
    }

    public function setCustomerType(CustomerTypeEnum $customerType): static
    {
        $this->customerType = $customerType;

        return $this;
    }

    public function getDefaultCurrency(): ?string
    {
        return $this->defaultCurrency;
    }

    public function setDefaultCurrency(?string $defaultCurrency): static
    {
        $this->defaultCurrency = $defaultCurrency;

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): static
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->setCustomer($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): static
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getCustomer() === $this) {
                $contact->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Quote>
     */
    public function getQuotes(): Collection
    {
        return $this->quotes;
    }

    public function addQuote(Quote $quote): static
    {
        if (!$this->quotes->contains($quote)) {
            $this->quotes->add($quote);
            $quote->setCustomer($this);
        }

        return $this;
    }

    public function removeQuote(Quote $quote): static
    {
        if ($this->quotes->removeElement($quote)) {
            // set the owning side to null (unless already changed)
            if ($quote->getCustomer() === $this) {
                $quote->setCustomer(null);
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
            $order->setCustomer($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCustomer() === $this) {
                $order->setCustomer(null);
            }
        }

        return $this;
    }
}

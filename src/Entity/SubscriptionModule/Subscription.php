<?php

namespace App\Entity\SubscriptionModule;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SubscriptionModule\SubscriptionRepository;
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

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
#[ApiResource]
#[Broadcast]
class Subscription
{
    use TimestampableTrait;
    use SoftDeletableTrait;
    use TenantAwareTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'subscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bundle $bundle = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'childSubscriptions')]
    private ?self $parentSubscription = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parentSubscription')]
    private Collection $childSubscriptions;

    #[ORM\Column]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $endDate = null;

    #[ORM\Column(enumType: SubscriptionStatusEnum::class)]
    private ?SubscriptionStatusEnum $status = null;

    #[ORM\Column]
    private ?bool $autoRenew = null;

    /**
     * @var Collection<int, License>
     */
    #[ORM\OneToMany(targetEntity: License::class, mappedBy: 'subscription', orphanRemoval: true)]
    private Collection $licenses;

    /**
     * @var Collection<int, MarketplaceTransaction>
     */
    #[ORM\OneToMany(targetEntity: MarketplaceTransaction::class, mappedBy: 'subscription')]
    private Collection $transactions;

    public function __construct()
    {
        $this->childSubscriptions = new ArrayCollection();
        $this->licenses = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

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

    public function getParentSubscription(): ?self
    {
        return $this->parentSubscription;
    }

    public function setParentSubscription(?self $parentSubscription): static
    {
        $this->parentSubscription = $parentSubscription;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildSubscriptions(): Collection
    {
        return $this->childSubscriptions;
    }

    public function addChildSubscription(self $childSubscription): static
    {
        if (!$this->childSubscriptions->contains($childSubscription)) {
            $this->childSubscriptions->add($childSubscription);
            $childSubscription->setParentSubscription($this);
        }

        return $this;
    }

    public function removeChildSubscription(self $childSubscription): static
    {
        if ($this->childSubscriptions->removeElement($childSubscription)) {
            // set the owning side to null (unless already changed)
            if ($childSubscription->getParentSubscription() === $this) {
                $childSubscription->setParentSubscription(null);
            }
        }

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getStatus(): ?SubscriptionStatusEnum
    {
        return $this->status;
    }

    public function setStatus(SubscriptionStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function isAutoRenew(): ?bool
    {
        return $this->autoRenew;
    }

    public function setAutoRenew(bool $autoRenew): static
    {
        $this->autoRenew = $autoRenew;

        return $this;
    }

    /**
     * @return Collection<int, License>
     */
    public function getLicenses(): Collection
    {
        return $this->licenses;
    }

    public function addLicense(License $license): static
    {
        if (!$this->licenses->contains($license)) {
            $this->licenses->add($license);
            $license->setSubscription($this);
        }

        return $this;
    }

    public function removeLicense(License $license): static
    {
        if ($this->licenses->removeElement($license)) {
            // set the owning side to null (unless already changed)
            if ($license->getSubscription() === $this) {
                $license->setSubscription(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MarketplaceTransaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(MarketplaceTransaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setSubscription($this);
        }

        return $this;
    }

    public function removeTransaction(MarketplaceTransaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getSubscription() === $this) {
                $transaction->setSubscription(null);
            }
        }

        return $this;
    }
}

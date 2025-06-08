<?php

declare(strict_types=1);

namespace App\Entity\SubscriptionModule;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SubscriptionModule\BundleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use App\Traits\BlameableTrait;
use App\Traits\TimestampableTrait;
use App\Traits\SoftDeletableTrait;
use App\Traits\TenantAwareTrait;

#[ORM\Entity(repositoryClass: BundleRepository::class)]
#[ApiResource]
#[Broadcast]
class Bundle
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
    private ?string $name = null;

    #[ORM\Column(enumType: BundleTypeEnum::class)]
    private ?BundleTypeEnum $type = null;

    #[ORM\Column(enumType: BundleStatusEnum::class)]
    private ?BundleStatusEnum $status = null;

    /**
     * @var Collection<int, BundleComponent>
     */
    #[ORM\OneToMany(targetEntity: BundleComponent::class, mappedBy: 'bundle', orphanRemoval: true)]
    private Collection $bundleComponents;

    /**
     * @var Collection<int, Subscription>
     */
    #[ORM\OneToMany(targetEntity: Subscription::class, mappedBy: 'bundle')]
    private Collection $subscriptions;

    public function __construct()
    {
        $this->bundleComponents = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
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

    public function getType(): ?BundleTypeEnum
    {
        return $this->type;
    }

    public function setType(BundleTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?BundleStatusEnum
    {
        return $this->status;
    }

    public function setStatus(BundleStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, BundleComponent>
     */
    public function getBundleComponents(): Collection
    {
        return $this->bundleComponents;
    }

    public function addBundleComponent(BundleComponent $bundleComponent): static
    {
        if (!$this->bundleComponents->contains($bundleComponent)) {
            $this->bundleComponents->add($bundleComponent);
            $bundleComponent->setBundle($this);
        }

        return $this;
    }

    public function removeBundleComponent(BundleComponent $bundleComponent): static
    {
        if ($this->bundleComponents->removeElement($bundleComponent)) {
            // set the owning side to null (unless already changed)
            if ($bundleComponent->getBundle() === $this) {
                $bundleComponent->setBundle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Subscription>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): static
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
            $subscription->setBundle($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): static
    {
        if ($this->subscriptions->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getBundle() === $this) {
                $subscription->setBundle(null);
            }
        }

        return $this;
    }
}

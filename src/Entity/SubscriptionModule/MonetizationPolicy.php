<?php

declare(strict_types=1);

namespace App\Entity\SubscriptionModule;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SubscriptionModule\MonetizationPolicyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use App\Traits\BlameableTrait;
use App\Traits\TimestampableTrait;
use App\Traits\SoftDeletableTrait;
use App\Traits\TenantAwareTrait;

#[ORM\Entity(repositoryClass: MonetizationPolicyRepository::class)]
#[ApiResource]
#[Broadcast]
class MonetizationPolicy
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

    #[ORM\Column(enumType: PricingModelEnum::class)]
    private ?PricingModelEnum $pricingModel = null;

    #[ORM\Column]
    private array $priceConfiguration = [];

    #[ORM\Column(length: 3)]
    private ?string $currency = null;

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

    public function getPricingModel(): ?PricingModelEnum
    {
        return $this->pricingModel;
    }

    public function setPricingModel(PricingModelEnum $pricingModel): static
    {
        $this->pricingModel = $pricingModel;

        return $this;
    }

    public function getPriceConfiguration(): array
    {
        return $this->priceConfiguration;
    }

    public function setPriceConfiguration(array $priceConfiguration): static
    {
        $this->priceConfiguration = $priceConfiguration;

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
}

<?php

declare(strict_types=1);

namespace App\Entity\SubscriptionModule;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SubscriptionModule\MarketplaceItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use App\Traits\BlameableTrait;
use App\Traits\TimestampableTrait;
use App\Traits\SoftDeletableTrait;
use App\Traits\TenantAwareTrait;

#[ORM\Entity(repositoryClass: MarketplaceItemRepository::class)]
#[ApiResource]
#[Broadcast]
class MarketplaceItem
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

    #[ORM\Column(type: Types::GUID)]
    private ?string $itemId = null;

    #[ORM\Column(enumType: MarketplaceItemTypeEnum::class)]
    private ?MarketplaceItemTypeEnum $type = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?MonetizationPolicy $monetizationPolicy = null;

    #[ORM\Column(enumType: ApprovalStatusEnum::class)]
    private ?ApprovalStatusEnum $approvalStatus = null;

    #[ORM\Column(nullable: true)]
    private ?array $compatibilityMatrix = null;

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

    public function getItemId(): ?string
    {
        return $this->itemId;
    }

    public function setItemId(string $itemId): static
    {
        $this->itemId = $itemId;

        return $this;
    }

    public function getType(): ?MarketplaceItemTypeEnum
    {
        return $this->type;
    }

    public function setType(MarketplaceItemTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getMonetizationPolicy(): ?MonetizationPolicy
    {
        return $this->monetizationPolicy;
    }

    public function setMonetizationPolicy(?MonetizationPolicy $monetizationPolicy): static
    {
        $this->monetizationPolicy = $monetizationPolicy;

        return $this;
    }

    public function getApprovalStatus(): ?ApprovalStatusEnum
    {
        return $this->approvalStatus;
    }

    public function setApprovalStatus(ApprovalStatusEnum $approvalStatus): static
    {
        $this->approvalStatus = $approvalStatus;

        return $this;
    }

    public function getCompatibilityMatrix(): ?array
    {
        return $this->compatibilityMatrix;
    }

    public function setCompatibilityMatrix(?array $compatibilityMatrix): static
    {
        $this->compatibilityMatrix = $compatibilityMatrix;

        return $this;
    }
}

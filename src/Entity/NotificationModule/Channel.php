<?php

declare(strict_types=1);

namespace App\Entity\NotificationModule;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\MultitenancyModule\Tenant;
use App\Repository\NotificationModule\ChannelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ChannelRepository::class)]
#[ApiResource]
#[Broadcast]
class Channel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'channels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tenant $tenant = null;

    #[ORM\Column(enumType: ChannelTypeEnum::class)]
    private ?ChannelTypeEnum $type = null;

    #[ORM\Column(enumType: ProviderEnum::class)]
    private ?ProviderEnum $provider = null;

    #[ORM\Column(nullable: true)]
    private ?array $config = null;

    #[ORM\Column]
    private ?bool $isDefault = null;

    /**
     * @var Collection<int, DeliveryRule>
     */
    #[ORM\ManyToMany(targetEntity: DeliveryRule::class, mappedBy: 'channels')]
    private Collection $deliveryRules;

    /**
     * @var Collection<int, NotificationLog>
     */
    #[ORM\OneToMany(targetEntity: NotificationLog::class, mappedBy: 'channel', orphanRemoval: true)]
    private Collection $notificationLogs;

    public function __construct()
    {
        $this->deliveryRules = new ArrayCollection();
        $this->notificationLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function setTenant(?Tenant $tenant): static
    {
        $this->tenant = $tenant;

        return $this;
    }

    public function getType(): ?ChannelTypeEnum
    {
        return $this->type;
    }

    public function setType(ChannelTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getProvider(): ?ProviderEnum
    {
        return $this->provider;
    }

    public function setProvider(ProviderEnum $provider): static
    {
        $this->provider = $provider;

        return $this;
    }

    public function getConfig(): ?array
    {
        return $this->config;
    }

    public function setConfig(?array $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function isDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): static
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * @return Collection<int, DeliveryRule>
     */
    public function getDeliveryRules(): Collection
    {
        return $this->deliveryRules;
    }

    public function addDeliveryRule(DeliveryRule $deliveryRule): static
    {
        if (!$this->deliveryRules->contains($deliveryRule)) {
            $this->deliveryRules->add($deliveryRule);
            $deliveryRule->addChannel($this);
        }

        return $this;
    }

    public function removeDeliveryRule(DeliveryRule $deliveryRule): static
    {
        if ($this->deliveryRules->removeElement($deliveryRule)) {
            $deliveryRule->removeChannel($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, NotificationLog>
     */
    public function getNotificationLogs(): Collection
    {
        return $this->notificationLogs;
    }

    public function addNotificationLog(NotificationLog $notificationLog): static
    {
        if (!$this->notificationLogs->contains($notificationLog)) {
            $this->notificationLogs->add($notificationLog);
            $notificationLog->setChannel($this);
        }

        return $this;
    }

    public function removeNotificationLog(NotificationLog $notificationLog): static
    {
        if ($this->notificationLogs->removeElement($notificationLog)) {
            // set the owning side to null (unless already changed)
            if ($notificationLog->getChannel() === $this) {
                $notificationLog->setChannel(null);
            }
        }

        return $this;
    }
}

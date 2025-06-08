<?php

declare(strict_types=1);

namespace App\Entity\NotificationModule;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\MultitenancyModule\Tenant;
use App\Entity\StorageManagementModule\Document;
use App\Entity\WorkflowModule\Task;
use App\Repository\NotificationModule\NotificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[ApiResource]
#[Broadcast]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    private ?Task $task = null;

    #[ORM\Column(enumType: NotificationTypeEnum::class)]
    private ?NotificationTypeEnum $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $sentAt = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tenant $tenant = null;

    #[ORM\Column(enumType: NotificationStatusEnum::class)]
    private ?NotificationStatusEnum $status = null;

    #[ORM\Column]
    private ?bool $isMandatoryAck = null;

    #[ORM\Column]
    private ?bool $isBlockingAlert = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    private ?Audience $audience = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $scheduledAt = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    private ?Document $linkedDocument = null;

    /**
     * @var Collection<int, DeliveryRule>
     */
    #[ORM\OneToMany(targetEntity: DeliveryRule::class, mappedBy: 'notification')]
    private Collection $deliveryRules;

    /**
     * @var Collection<int, Acknowledgement>
     */
    #[ORM\OneToMany(targetEntity: Acknowledgement::class, mappedBy: 'notification')]
    private Collection $acknowledgements;

    /**
     * @var Collection<int, NotificationLog>
     */
    #[ORM\OneToMany(targetEntity: NotificationLog::class, mappedBy: 'notification', orphanRemoval: true)]
    private Collection $notificationLogs;

    public function __construct()
    {
        $this->deliveryRules = new ArrayCollection();
        $this->acknowledgements = new ArrayCollection();
        $this->notificationLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): static
    {
        $this->task = $task;

        return $this;
    }

    public function getType(): ?NotificationTypeEnum
    {
        return $this->type;
    }

    public function setType(NotificationTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getSentAt(): ?\DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeImmutable $sentAt): static
    {
        $this->sentAt = $sentAt;

        return $this;
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

    public function getStatus(): ?NotificationStatusEnum
    {
        return $this->status;
    }

    public function setStatus(NotificationStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function isMandatoryAck(): ?bool
    {
        return $this->isMandatoryAck;
    }

    public function setIsMandatoryAck(bool $isMandatoryAck): static
    {
        $this->isMandatoryAck = $isMandatoryAck;

        return $this;
    }

    public function isBlockingAlert(): ?bool
    {
        return $this->isBlockingAlert;
    }

    public function setIsBlockingAlert(bool $isBlockingAlert): static
    {
        $this->isBlockingAlert = $isBlockingAlert;

        return $this;
    }

    public function getAudience(): ?Audience
    {
        return $this->audience;
    }

    public function setAudience(?Audience $audience): static
    {
        $this->audience = $audience;

        return $this;
    }

    public function getScheduledAt(): ?\DateTime
    {
        return $this->scheduledAt;
    }

    public function setScheduledAt(?\DateTime $scheduledAt): static
    {
        $this->scheduledAt = $scheduledAt;

        return $this;
    }

    public function getLinkedDocument(): ?Document
    {
        return $this->linkedDocument;
    }

    public function setLinkedDocument(?Document $linkedDocument): static
    {
        $this->linkedDocument = $linkedDocument;

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
            $deliveryRule->setNotification($this);
        }

        return $this;
    }

    public function removeDeliveryRule(DeliveryRule $deliveryRule): static
    {
        if ($this->deliveryRules->removeElement($deliveryRule)) {
            // set the owning side to null (unless already changed)
            if ($deliveryRule->getNotification() === $this) {
                $deliveryRule->setNotification(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Acknowledgement>
     */
    public function getAcknowledgements(): Collection
    {
        return $this->acknowledgements;
    }

    public function addAcknowledgement(Acknowledgement $acknowledgement): static
    {
        if (!$this->acknowledgements->contains($acknowledgement)) {
            $this->acknowledgements->add($acknowledgement);
            $acknowledgement->setNotification($this);
        }

        return $this;
    }

    public function removeAcknowledgement(Acknowledgement $acknowledgement): static
    {
        if ($this->acknowledgements->removeElement($acknowledgement)) {
            // set the owning side to null (unless already changed)
            if ($acknowledgement->getNotification() === $this) {
                $acknowledgement->setNotification(null);
            }
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
            $notificationLog->setNotification($this);
        }

        return $this;
    }

    public function removeNotificationLog(NotificationLog $notificationLog): static
    {
        if ($this->notificationLogs->removeElement($notificationLog)) {
            // set the owning side to null (unless already changed)
            if ($notificationLog->getNotification() === $this) {
                $notificationLog->setNotification(null);
            }
        }

        return $this;
    }
}

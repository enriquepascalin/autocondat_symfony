<?php

declare(strict_types=1);

namespace App\Entity\MultitenancyModule;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\AuthenticationModule\Role;
use App\Entity\NotificationModule\Acknowledgement;
use App\Entity\NotificationModule\Audience;
use App\Entity\NotificationModule\Channel;
use App\Entity\NotificationModule\DeliveryRule;
use App\Entity\NotificationModule\Notification;
use App\Entity\ProjectModule\Project;
use App\Entity\ProjectModule\ProjectPhase;
use App\Entity\ProjectModule\ProjectPhaseAssignment;
use App\Entity\StorageManagementModule\Document;
use App\Entity\SubscriptionModule\MarketplaceTransaction;
use App\Entity\WorkflowModule\Calendar;
use App\Repository\MultitenancyModule\TenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: TenantRepository::class)]
#[ApiResource]
#[Broadcast]
class Tenant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $isFinancial = null;

    #[ORM\Column]
    private ?bool $isOperational = null;

    #[ORM\Column(length: 50)]
    private ?string $code = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $parent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $children;

    /**
     * @var Collection<int, TenantConfig>
     */
    #[ORM\OneToMany(targetEntity: TenantConfig::class, mappedBy: 'tenant', orphanRemoval: true)]
    private Collection $configs;

    /**
     * @var Collection<int, Segment>
     */
    #[ORM\ManyToMany(targetEntity: Segment::class, inversedBy: 'tenants')]
    private Collection $segments;

    /**
     * @var Collection<int, TenantAuditLog>
     */
    #[ORM\OneToMany(targetEntity: TenantAuditLog::class, mappedBy: 'tenant', orphanRemoval: true)]
    private Collection $auditLogs;

    /**
     * @var Collection<int, Calendar>
     */
    #[ORM\OneToMany(targetEntity: Calendar::class, mappedBy: 'tenant', orphanRemoval: true)]
    private Collection $calendars;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\OneToMany(targetEntity: Project::class, mappedBy: 'tenant')]
    private Collection $projects;

    /**
     * @var Collection<int, ProjectPhase>
     */
    #[ORM\OneToMany(targetEntity: ProjectPhase::class, mappedBy: 'tenant', orphanRemoval: true)]
    private Collection $projectPhases;

    /**
     * @var Collection<int, ProjectPhaseAssignment>
     */
    #[ORM\OneToMany(targetEntity: ProjectPhaseAssignment::class, mappedBy: 'tenant')]
    private Collection $projectPhaseAssignments;

    /**
     * @var Collection<int, Document>
     */
    #[ORM\OneToMany(targetEntity: Document::class, mappedBy: 'tenant', orphanRemoval: true)]
    private Collection $documents;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'tenant', orphanRemoval: true)]
    private Collection $notifications;

    /**
     * @var Collection<int, Audience>
     */
    #[ORM\OneToMany(targetEntity: Audience::class, mappedBy: 'tenant', orphanRemoval: true)]
    private Collection $audiences;

    /**
     * @var Collection<int, DeliveryRule>
     */
    #[ORM\OneToMany(targetEntity: DeliveryRule::class, mappedBy: 'tenant', orphanRemoval: true)]
    private Collection $deliveryRules;

    /**
     * @var Collection<int, Channel>
     */
    #[ORM\OneToMany(targetEntity: Channel::class, mappedBy: 'tenant', orphanRemoval: true)]
    private Collection $channels;

    /**
     * @var Collection<int, Acknowledgement>
     */
    #[ORM\OneToMany(targetEntity: Acknowledgement::class, mappedBy: 'tenant', orphanRemoval: true)]
    private Collection $acknowledgements;

    /**
     * @var Collection<int, Role>
     */
    #[ORM\OneToMany(targetEntity: Role::class, mappedBy: 'tenant')]
    private Collection $roles;

    /**
     * @var Collection<int, MarketplaceTransaction>
     */
    #[ORM\OneToMany(targetEntity: MarketplaceTransaction::class, mappedBy: 'subscriptor', orphanRemoval: true)]
    private Collection $marketplaceTransactions;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->configs = new ArrayCollection();
        $this->segments = new ArrayCollection();
        $this->auditLogs = new ArrayCollection();
        $this->calendars = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->projectPhases = new ArrayCollection();
        $this->projectPhaseAssignments = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->audiences = new ArrayCollection();
        $this->deliveryRules = new ArrayCollection();
        $this->channels = new ArrayCollection();
        $this->acknowledgements = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->marketplaceTransactions = new ArrayCollection();
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

    public function isFinancial(): ?bool
    {
        return $this->isFinancial;
    }

    public function setIsFinancial(bool $isFinancial): static
    {
        $this->isFinancial = $isFinancial;

        return $this;
    }

    public function isOperational(): ?bool
    {
        return $this->isOperational;
    }

    public function setIsOperational(bool $isOperational): static
    {
        $this->isOperational = $isOperational;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): static
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TenantConfig>
     */
    public function getConfigs(): Collection
    {
        return $this->configs;
    }

    public function addConfig(TenantConfig $config): static
    {
        if (!$this->configs->contains($config)) {
            $this->configs->add($config);
            $config->setTenant($this);
        }

        return $this;
    }

    public function removeConfig(TenantConfig $config): static
    {
        if ($this->configs->removeElement($config)) {
            // set the owning side to null (unless already changed)
            if ($config->getTenant() === $this) {
                $config->setTenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Segment>
     */
    public function getSegments(): Collection
    {
        return $this->segments;
    }

    public function addSegment(Segment $segment): static
    {
        if (!$this->segments->contains($segment)) {
            $this->segments->add($segment);
        }

        return $this;
    }

    public function removeSegment(Segment $segment): static
    {
        $this->segments->removeElement($segment);

        return $this;
    }

    /**
     * @return Collection<int, TenantAuditLog>
     */
    public function getAuditLogs(): Collection
    {
        return $this->auditLogs;
    }

    public function addAuditLog(TenantAuditLog $auditLog): static
    {
        if (!$this->auditLogs->contains($auditLog)) {
            $this->auditLogs->add($auditLog);
            $auditLog->setTenant($this);
        }

        return $this;
    }

    public function removeAuditLog(TenantAuditLog $auditLog): static
    {
        if ($this->auditLogs->removeElement($auditLog)) {
            // set the owning side to null (unless already changed)
            if ($auditLog->getTenant() === $this) {
                $auditLog->setTenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Calendar>
     */
    public function getCalendars(): Collection
    {
        return $this->calendars;
    }

    public function addCalendar(Calendar $calendar): static
    {
        if (!$this->calendars->contains($calendar)) {
            $this->calendars->add($calendar);
            $calendar->setTenant($this);
        }

        return $this;
    }

    public function removeCalendar(Calendar $calendar): static
    {
        if ($this->calendars->removeElement($calendar)) {
            // set the owning side to null (unless already changed)
            if ($calendar->getTenant() === $this) {
                $calendar->setTenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setTenant($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getTenant() === $this) {
                $project->setTenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProjectPhase>
     */
    public function getProjectPhases(): Collection
    {
        return $this->projectPhases;
    }

    public function addProjectPhase(ProjectPhase $projectPhase): static
    {
        if (!$this->projectPhases->contains($projectPhase)) {
            $this->projectPhases->add($projectPhase);
            $projectPhase->setTenant($this);
        }

        return $this;
    }

    public function removeProjectPhase(ProjectPhase $projectPhase): static
    {
        if ($this->projectPhases->removeElement($projectPhase)) {
            // set the owning side to null (unless already changed)
            if ($projectPhase->getTenant() === $this) {
                $projectPhase->setTenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProjectPhaseAssignment>
     */
    public function getProjectPhaseAssignments(): Collection
    {
        return $this->projectPhaseAssignments;
    }

    public function addProjectPhaseAssignment(ProjectPhaseAssignment $projectPhaseAssignment): static
    {
        if (!$this->projectPhaseAssignments->contains($projectPhaseAssignment)) {
            $this->projectPhaseAssignments->add($projectPhaseAssignment);
            $projectPhaseAssignment->setTenant($this);
        }

        return $this;
    }

    public function removeProjectPhaseAssignment(ProjectPhaseAssignment $projectPhaseAssignment): static
    {
        if ($this->projectPhaseAssignments->removeElement($projectPhaseAssignment)) {
            // set the owning side to null (unless already changed)
            if ($projectPhaseAssignment->getTenant() === $this) {
                $projectPhaseAssignment->setTenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setTenant($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): static
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getTenant() === $this) {
                $document->setTenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setTenant($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getTenant() === $this) {
                $notification->setTenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Audience>
     */
    public function getAudiences(): Collection
    {
        return $this->audiences;
    }

    public function addAudience(Audience $audience): static
    {
        if (!$this->audiences->contains($audience)) {
            $this->audiences->add($audience);
            $audience->setTenant($this);
        }

        return $this;
    }

    public function removeAudience(Audience $audience): static
    {
        if ($this->audiences->removeElement($audience)) {
            // set the owning side to null (unless already changed)
            if ($audience->getTenant() === $this) {
                $audience->setTenant(null);
            }
        }

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
            $deliveryRule->setTenant($this);
        }

        return $this;
    }

    public function removeDeliveryRule(DeliveryRule $deliveryRule): static
    {
        if ($this->deliveryRules->removeElement($deliveryRule)) {
            // set the owning side to null (unless already changed)
            if ($deliveryRule->getTenant() === $this) {
                $deliveryRule->setTenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Channel>
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function addChannel(Channel $channel): static
    {
        if (!$this->channels->contains($channel)) {
            $this->channels->add($channel);
            $channel->setTenant($this);
        }

        return $this;
    }

    public function removeChannel(Channel $channel): static
    {
        if ($this->channels->removeElement($channel)) {
            // set the owning side to null (unless already changed)
            if ($channel->getTenant() === $this) {
                $channel->setTenant(null);
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
            $acknowledgement->setTenant($this);
        }

        return $this;
    }

    public function removeAcknowledgement(Acknowledgement $acknowledgement): static
    {
        if ($this->acknowledgements->removeElement($acknowledgement)) {
            // set the owning side to null (unless already changed)
            if ($acknowledgement->getTenant() === $this) {
                $acknowledgement->setTenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Role>
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): static
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
            $role->setTenant($this);
        }

        return $this;
    }

    public function removeRole(Role $role): static
    {
        if ($this->roles->removeElement($role)) {
            // set the owning side to null (unless already changed)
            if ($role->getTenant() === $this) {
                $role->setTenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MarketplaceTransaction>
     */
    public function getMarketplaceTransactions(): Collection
    {
        return $this->marketplaceTransactions;
    }

    public function addMarketplaceTransaction(MarketplaceTransaction $marketplaceTransaction): static
    {
        if (!$this->marketplaceTransactions->contains($marketplaceTransaction)) {
            $this->marketplaceTransactions->add($marketplaceTransaction);
            $marketplaceTransaction->setSubscriptor($this);
        }

        return $this;
    }

    public function removeMarketplaceTransaction(MarketplaceTransaction $marketplaceTransaction): static
    {
        if ($this->marketplaceTransactions->removeElement($marketplaceTransaction)) {
            // set the owning side to null (unless already changed)
            if ($marketplaceTransaction->getSubscriptor() === $this) {
                $marketplaceTransaction->setSubscriptor(null);
            }
        }

        return $this;
    }
}

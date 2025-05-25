<?php

namespace App\Entity\MultitenancyModule;

use ApiPlatform\Metadata\ApiResource;
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

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->configs = new ArrayCollection();
        $this->segments = new ArrayCollection();
        $this->auditLogs = new ArrayCollection();
        $this->calendars = new ArrayCollection();
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
}

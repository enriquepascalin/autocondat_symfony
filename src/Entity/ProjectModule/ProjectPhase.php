<?php

declare(strict_types=1);

namespace App\Entity\ProjectModule;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\AuthenticationModule\User;
use App\Entity\MultitenancyModule\Tenant;
use App\Entity\WorkflowModule\WorkflowExecution;
use App\Repository\ProjectModule\ProjectPhaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ProjectPhaseRepository::class)]
#[ApiResource]
#[Broadcast]
class ProjectPhase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'projectPhases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tenant $tenant = null;

    #[ORM\ManyToOne(inversedBy: 'projectPhases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(enumType: ProjectTypeEnum::class)]
    private ?ProjectTypeEnum $type = null;

    #[ORM\Column]
    private ?\DateTime $startDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $endDate = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'childrenPhases')]
    private ?self $parentPhase = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parentPhase')]
    private Collection $childrenPhases;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'dependenants')]
    private Collection $dependencies;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'dependencies')]
    private Collection $dependenants;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'asignedProjectPhases')]
    private Collection $assignees;

    #[ORM\OneToOne(inversedBy: 'projectPhase', cascade: ['persist', 'remove'])]
    private ?WorkflowExecution $workflowExecution = null;

    #[ORM\Column(enumType: PhaseStatusEnum::class)]
    private ?PhaseStatusEnum $status = null;

    /**
     * @var Collection<int, ProjectPhaseAssignment>
     */
    #[ORM\OneToMany(targetEntity: ProjectPhaseAssignment::class, mappedBy: 'phase')]
    private Collection $projectPhaseAssignments;

    public function __construct()
    {
        $this->childrenPhases = new ArrayCollection();
        $this->dependencies = new ArrayCollection();
        $this->dependenants = new ArrayCollection();
        $this->assignees = new ArrayCollection();
        $this->projectPhaseAssignments = new ArrayCollection();
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

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
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

    public function getType(): ?ProjectTypeEnum
    {
        return $this->type;
    }

    public function setType(ProjectTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): static
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

    public function getParentPhase(): ?self
    {
        return $this->parentPhase;
    }

    public function setParentPhase(?self $parentPhase): static
    {
        $this->parentPhase = $parentPhase;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildrenPhases(): Collection
    {
        return $this->childrenPhases;
    }

    public function addChildrenPhase(self $childrenPhase): static
    {
        if (!$this->childrenPhases->contains($childrenPhase)) {
            $this->childrenPhases->add($childrenPhase);
            $childrenPhase->setParentPhase($this);
        }

        return $this;
    }

    public function removeChildrenPhase(self $childrenPhase): static
    {
        if ($this->childrenPhases->removeElement($childrenPhase)) {
            // set the owning side to null (unless already changed)
            if ($childrenPhase->getParentPhase() === $this) {
                $childrenPhase->setParentPhase(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getDependencies(): Collection
    {
        return $this->dependencies;
    }

    public function addDependency(self $dependency): static
    {
        if (!$this->dependencies->contains($dependency)) {
            $this->dependencies->add($dependency);
        }

        return $this;
    }

    public function removeDependency(self $dependency): static
    {
        $this->dependencies->removeElement($dependency);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getDependenants(): Collection
    {
        return $this->dependenants;
    }

    public function addDependenant(self $dependenant): static
    {
        if (!$this->dependenants->contains($dependenant)) {
            $this->dependenants->add($dependenant);
            $dependenant->addDependency($this);
        }

        return $this;
    }

    public function removeDependenant(self $dependenant): static
    {
        if ($this->dependenants->removeElement($dependenant)) {
            $dependenant->removeDependency($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAssignees(): Collection
    {
        return $this->assignees;
    }

    public function addAssignee(User $assignee): static
    {
        if (!$this->assignees->contains($assignee)) {
            $this->assignees->add($assignee);
        }

        return $this;
    }

    public function removeAssignee(User $assignee): static
    {
        $this->assignees->removeElement($assignee);

        return $this;
    }

    public function getWorkflowExecution(): ?WorkflowExecution
    {
        return $this->workflowExecution;
    }

    public function setWorkflowExecution(?WorkflowExecution $workflowExecution): static
    {
        $this->workflowExecution = $workflowExecution;

        return $this;
    }

    public function getStatus(): ?PhaseStatusEnum
    {
        return $this->status;
    }

    public function setStatus(PhaseStatusEnum $status): static
    {
        $this->status = $status;

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
            $projectPhaseAssignment->setPhase($this);
        }

        return $this;
    }

    public function removeProjectPhaseAssignment(ProjectPhaseAssignment $projectPhaseAssignment): static
    {
        if ($this->projectPhaseAssignments->removeElement($projectPhaseAssignment)) {
            // set the owning side to null (unless already changed)
            if ($projectPhaseAssignment->getPhase() === $this) {
                $projectPhaseAssignment->setPhase(null);
            }
        }

        return $this;
    }
}

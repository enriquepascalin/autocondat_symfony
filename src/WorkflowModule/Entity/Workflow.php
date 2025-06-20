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

namespace App\WorkflowModule\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\SubscriptionModule\Entity\Feature;
use App\WorkflowModule\Repository\WorkflowRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use App\Traits\BlameableTrait;
use App\Traits\TimestampableTrait;
use App\Traits\SoftDeletableTrait;
use App\Traits\TenantAwareTrait;

#[ORM\Entity(repositoryClass: WorkflowRepository::class)]
#[ApiResource]
#[Broadcast]
class Workflow
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

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $version = null;

    #[ORM\Column]
    private ?bool $isEnabled = null;

    #[ORM\Column]
    private ?int $priority = null;

    /**
     * @var Collection<int, State>
     */
    #[ORM\OneToMany(targetEntity: State::class, mappedBy: 'workflow', orphanRemoval: true)]
    private Collection $states;

    /**
     * @var Collection<int, Transition>
     */
    #[ORM\OneToMany(targetEntity: Transition::class, mappedBy: 'workflow', orphanRemoval: true)]
    private Collection $transitions;

    /**
     * @var Collection<int, WorkflowExecution>
     */
    #[ORM\OneToMany(targetEntity: WorkflowExecution::class, mappedBy: 'workflow', orphanRemoval: true)]
    private Collection $executions;

    #[ORM\ManyToOne(inversedBy: 'workflows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Feature $feature = null;

    #[ORM\Column(nullable: true)]
    private ?array $entitlementRequirements = null;

    public function __construct()
    {
        $this->states = new ArrayCollection();
        $this->transitions = new ArrayCollection();
        $this->executions = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): static
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return Collection<int, State>
     */
    public function getStates(): Collection
    {
        return $this->states;
    }

    public function addState(State $state): static
    {
        if (!$this->states->contains($state)) {
            $this->states->add($state);
            $state->setWorkflow($this);
        }

        return $this;
    }

    public function removeState(State $state): static
    {
        if ($this->states->removeElement($state)) {
            // set the owning side to null (unless already changed)
            if ($state->getWorkflow() === $this) {
                $state->setWorkflow(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transition>
     */
    public function getTransitions(): Collection
    {
        return $this->transitions;
    }

    public function addTransition(Transition $transition): static
    {
        if (!$this->transitions->contains($transition)) {
            $this->transitions->add($transition);
            $transition->setWorkflow($this);
        }

        return $this;
    }

    public function removeTransition(Transition $transition): static
    {
        if ($this->transitions->removeElement($transition)) {
            // set the owning side to null (unless already changed)
            if ($transition->getWorkflow() === $this) {
                $transition->setWorkflow(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WorkflowExecution>
     */
    public function getExecutions(): Collection
    {
        return $this->executions;
    }

    public function addExecution(WorkflowExecution $execution): static
    {
        if (!$this->executions->contains($execution)) {
            $this->executions->add($execution);
            $execution->setWorkflow($this);
        }

        return $this;
    }

    public function removeExecution(WorkflowExecution $execution): static
    {
        if ($this->executions->removeElement($execution)) {
            // set the owning side to null (unless already changed)
            if ($execution->getWorkflow() === $this) {
                $execution->setWorkflow(null);
            }
        }

        return $this;
    }

    public function getFeature(): ?Feature
    {
        return $this->feature;
    }

    public function setFeature(?Feature $feature): static
    {
        $this->feature = $feature;

        return $this;
    }

    public function getEntitlementRequirements(): ?array
    {
        return $this->entitlementRequirements;
    }

    public function setEntitlementRequirements(?array $entitlementRequirements): static
    {
        $this->entitlementRequirements = $entitlementRequirements;

        return $this;
    }
}

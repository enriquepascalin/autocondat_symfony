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

namespace App\Entity\WorkflowModule;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\WorkflowModule\StateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: StateRepository::class)]
#[ApiResource]
#[Broadcast]
class State
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(enumType: StateTypeEnum::class)]
    private ?StateTypeEnum $type = null;

    #[ORM\ManyToOne(inversedBy: 'states')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Workflow $workflow = null;

    #[ORM\Column]
    private ?int $executionOrder = null;

    #[ORM\Column(nullable: true)]
    private ?array $metadata = null;

    /**
     * @var Collection<int, Transition>
     */
    #[ORM\OneToMany(targetEntity: Transition::class, mappedBy: 'sourcecState')]
    private Collection $sourceTransitions;

    /**
     * @var Collection<int, Transition>
     */
    #[ORM\OneToMany(targetEntity: Transition::class, mappedBy: 'targetState')]
    private Collection $targetTransitions;

    /**
     * @var Collection<int, WorkflowExecution>
     */
    #[ORM\OneToMany(targetEntity: WorkflowExecution::class, mappedBy: 'currentState', orphanRemoval: true)]
    private Collection $executions;

    public function __construct()
    {
        $this->sourceTransitions = new ArrayCollection();
        $this->targetTransitions = new ArrayCollection();
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

    public function getType(): ?StateTypeEnum
    {
        return $this->type;
    }

    public function setType(StateTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getWorkflow(): ?Workflow
    {
        return $this->workflow;
    }

    public function setWorkflow(?Workflow $workflow): static
    {
        $this->workflow = $workflow;

        return $this;
    }

    public function getExecutionOrder(): ?int
    {
        return $this->executionOrder;
    }

    public function setExecutionOrder(int $executionOrder): static
    {
        $this->executionOrder = $executionOrder;

        return $this;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(?array $metadata): static
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @return Collection<int, Transition>
     */
    public function getSourceTransitions(): Collection
    {
        return $this->sourceTransitions;
    }

    public function addSourceTransition(Transition $sourceTransition): static
    {
        if (!$this->sourceTransitions->contains($sourceTransition)) {
            $this->sourceTransitions->add($sourceTransition);
            $sourceTransition->setSourcecState($this);
        }

        return $this;
    }

    public function removeSourceTransition(Transition $sourceTransition): static
    {
        if ($this->sourceTransitions->removeElement($sourceTransition)) {
            // set the owning side to null (unless already changed)
            if ($sourceTransition->getSourcecState() === $this) {
                $sourceTransition->setSourcecState(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transition>
     */
    public function getTargetTransitions(): Collection
    {
        return $this->targetTransitions;
    }

    public function addTargetTransition(Transition $targetTransition): static
    {
        if (!$this->targetTransitions->contains($targetTransition)) {
            $this->targetTransitions->add($targetTransition);
            $targetTransition->setTargetState($this);
        }

        return $this;
    }

    public function removeTargetTransition(Transition $targetTransition): static
    {
        if ($this->targetTransitions->removeElement($targetTransition)) {
            // set the owning side to null (unless already changed)
            if ($targetTransition->getTargetState() === $this) {
                $targetTransition->setTargetState(null);
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
            $execution->setCurrentState($this);
        }

        return $this;
    }

    public function removeExecution(WorkflowExecution $execution): static
    {
        if ($this->executions->removeElement($execution)) {
            // set the owning side to null (unless already changed)
            if ($execution->getCurrentState() === $this) {
                $execution->setCurrentState(null);
            }
        }

        return $this;
    }
}

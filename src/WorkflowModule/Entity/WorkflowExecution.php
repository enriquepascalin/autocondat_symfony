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
use App\MultitenancyModule\Entity\Tenant;
use App\ProjectModule\Entity\ProjectPhase;
use App\WorkflowModule\Repository\WorkflowExecutionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: WorkflowExecutionRepository::class)]
#[ApiResource]
#[Broadcast]
class WorkflowExecution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'executions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Workflow $workflow = null;

    #[ORM\ManyToOne(inversedBy: 'executions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?State $currentState = null;

    #[ORM\Column(nullable: true)]
    private ?array $context = null;

    #[ORM\Column(enumType: WorkflowExecutionStatusEnum::class)]
    private ?WorkflowExecutionStatusEnum $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endedAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tenant $tenant = null;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'workflowExecution', orphanRemoval: true)]
    private Collection $tasks;

    #[ORM\OneToOne(mappedBy: 'workflowExecution', cascade: ['persist', 'remove'])]
    private ?ProjectPhase $projectPhase = null;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCurrentState(): ?State
    {
        return $this->currentState;
    }

    public function setCurrentState(?State $currentState): static
    {
        $this->currentState = $currentState;

        return $this;
    }

    public function getContext(): ?array
    {
        return $this->context;
    }

    public function setContext(?array $context): static
    {
        $this->context = $context;

        return $this;
    }

    public function getStatus(): ?WorkflowExecutionStatusEnum
    {
        return $this->status;
    }

    public function setStatus(WorkflowExecutionStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function setEndedAt(?\DateTimeImmutable $endedAt): static
    {
        $this->endedAt = $endedAt;

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

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setWorkflowExecution($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getWorkflowExecution() === $this) {
                $task->setWorkflowExecution(null);
            }
        }

        return $this;
    }

    public function getProjectPhase(): ?ProjectPhase
    {
        return $this->projectPhase;
    }

    public function setProjectPhase(?ProjectPhase $projectPhase): static
    {
        // unset the owning side of the relation if necessary
        if (null === $projectPhase && null !== $this->projectPhase) {
            $this->projectPhase->setWorkflowExecution(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $projectPhase && $projectPhase->getWorkflowExecution() !== $this) {
            $projectPhase->setWorkflowExecution($this);
        }

        $this->projectPhase = $projectPhase;

        return $this;
    }
}

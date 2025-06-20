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
use App\WorkflowModule\Repository\TransitionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: TransitionRepository::class)]
#[ApiResource]
#[Broadcast]
class Transition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'sourceTransitions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?State $sourcecState = null;

    #[ORM\ManyToOne(inversedBy: 'targetTransitions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?State $targetState = null;

    #[ORM\ManyToOne(inversedBy: 'transitions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Workflow $workflow = null;

    #[ORM\Column(enumType: TransitionConditionModeEnum::class)]
    private ?TransitionConditionModeEnum $conditionMode = null;

    /**
     * @var Collection<int, Trigger>
     */
    #[ORM\OneToMany(targetEntity: Trigger::class, mappedBy: 'transition', orphanRemoval: true)]
    private Collection $triggers;

    public function __construct()
    {
        $this->triggers = new ArrayCollection();
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

    public function getSourcecState(): ?State
    {
        return $this->sourcecState;
    }

    public function setSourcecState(?State $sourcecState): static
    {
        $this->sourcecState = $sourcecState;

        return $this;
    }

    public function getTargetState(): ?State
    {
        return $this->targetState;
    }

    public function setTargetState(?State $targetState): static
    {
        $this->targetState = $targetState;

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

    public function getConditionMode(): ?TransitionConditionModeEnum
    {
        return $this->conditionMode;
    }

    public function setConditionMode(TransitionConditionModeEnum $conditionMode): static
    {
        $this->conditionMode = $conditionMode;

        return $this;
    }

    /**
     * @return Collection<int, Trigger>
     */
    public function getTriggers(): Collection
    {
        return $this->triggers;
    }

    public function addTrigger(Trigger $trigger): static
    {
        if (!$this->triggers->contains($trigger)) {
            $this->triggers->add($trigger);
            $trigger->setTransition($this);
        }

        return $this;
    }

    public function removeTrigger(Trigger $trigger): static
    {
        if ($this->triggers->removeElement($trigger)) {
            // set the owning side to null (unless already changed)
            if ($trigger->getTransition() === $this) {
                $trigger->setTransition(null);
            }
        }

        return $this;
    }
}

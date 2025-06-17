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
use App\Repository\WorkflowModule\TriggerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: TriggerRepository::class)]
#[ApiResource]
#[Broadcast]
class Trigger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: TriggerTypeEnum::class)]
    private ?TriggerTypeEnum $type = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'triggers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Transition $transition = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?TriggerTypeEnum
    {
        return $this->type;
    }

    public function setType(TriggerTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getTransition(): ?Transition
    {
        return $this->transition;
    }

    public function setTransition(?Transition $transition): static
    {
        $this->transition = $transition;

        return $this;
    }
}

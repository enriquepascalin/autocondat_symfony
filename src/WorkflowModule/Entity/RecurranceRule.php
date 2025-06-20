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
use App\WorkflowModule\Repository\RecurranceRuleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: RecurranceRuleRepository::class)]
#[ApiResource]
#[Broadcast]
class RecurranceRule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: RecurranceRuleFrequencyEnum::class)]
    private ?RecurranceRuleFrequencyEnum $frequency = null;

    #[ORM\Column]
    private ?int $interval = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $until = null;

    #[ORM\Column(nullable: true)]
    private ?array $excludedDates = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFrequency(): ?RecurranceRuleFrequencyEnum
    {
        return $this->frequency;
    }

    public function setFrequency(RecurranceRuleFrequencyEnum $frequency): static
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function getInterval(): ?int
    {
        return $this->interval;
    }

    public function setInterval(int $interval): static
    {
        $this->interval = $interval;

        return $this;
    }

    public function getUntil(): ?\DateTime
    {
        return $this->until;
    }

    public function setUntil(?\DateTime $until): static
    {
        $this->until = $until;

        return $this;
    }

    public function getExcludedDates(): ?array
    {
        return $this->excludedDates;
    }

    public function setExcludedDates(?array $excludedDates): static
    {
        $this->excludedDates = $excludedDates;

        return $this;
    }
}

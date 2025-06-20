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


namespace App\AuditTrailModule\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\AuditTrailModule\Repository\AuditRetentionPolicyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: AuditRetentionPolicyRepository::class)]
#[ApiResource]
#[Broadcast]
class AuditRetentionPolicy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\Column]
    private ?int $keep_days = null;

    #[ORM\Column(nullable: true)]
    private ?int $anonymizeAfterDays = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getKeepDays(): ?int
    {
        return $this->keep_days;
    }

    public function setKeepDays(int $keep_days): static
    {
        $this->keep_days = $keep_days;

        return $this;
    }

    public function getAnonymizeAfterDays(): ?int
    {
        return $this->anonymizeAfterDays;
    }

    public function setAnonymizeAfterDays(?int $anonymizeAfterDays): static
    {
        $this->anonymizeAfterDays = $anonymizeAfterDays;

        return $this;
    }
}

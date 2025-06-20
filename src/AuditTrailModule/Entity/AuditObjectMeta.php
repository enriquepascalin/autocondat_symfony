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
use App\AuditTrailModule\Repository\AuditObjectMetaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: AuditObjectMetaRepository::class)]
#[ApiResource]
#[Broadcast]
class AuditObjectMeta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $object_class = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $displayName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $compositeKey = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjectClass(): ?string
    {
        return $this->object_class;
    }

    public function setObjectClass(string $object_class): static
    {
        $this->object_class = $object_class;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getCompositeKey(): ?string
    {
        return $this->compositeKey;
    }

    public function setCompositeKey(?string $compositeKey): static
    {
        $this->compositeKey = $compositeKey;

        return $this;
    }
}

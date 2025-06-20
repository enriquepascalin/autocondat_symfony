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
use App\AuditTrailModule\Repository\AuditExportRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: AuditExportRepository::class)]
#[ApiResource]
#[Broadcast]
class AuditExport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'auditExports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AuditActor $requestedBy = null;

    #[ORM\Column]
    private array $filterJson = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestedBy(): ?AuditActor
    {
        return $this->requestedBy;
    }

    public function setRequestedBy(?AuditActor $requestedBy): static
    {
        $this->requestedBy = $requestedBy;

        return $this;
    }

    public function getFilterJson(): array
    {
        return $this->filterJson;
    }

    public function setFilterJson(array $filterJson): static
    {
        $this->filterJson = $filterJson;

        return $this;
    }
}

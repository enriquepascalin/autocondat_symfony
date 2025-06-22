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
use App\AuditTrailModule\Entity\AuditActionEnum;
use App\AuditTrailModule\Repository\AuditEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: AuditEventRepository::class)]
#[ApiResource]
#[Broadcast]
class AuditEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $object_class = null;

    #[ORM\Column]
    private ?int $object_id = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $ip = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $user_agent = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $source = null;

    #[ORM\Column(enumType: AuditActionEnum::class)]
    private ?AuditActionEnum $action = null;

    #[ORM\Column(enumType: AuditSeverityEnum::class)]
    private ?AuditSeverityEnum $severity = null;

    /**
     * @var Collection<int, AuditChangeSet>
     */
    #[ORM\OneToMany(targetEntity: AuditChangeSet::class, mappedBy: 'event', orphanRemoval: true)]
    private Collection $auditChangeSets;

    /**
     * @var Collection<int, AuditTag>
     */
    #[ORM\OneToMany(targetEntity: AuditTag::class, mappedBy: 'event')]
    private Collection $tags;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?AuditActor $actor = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?AuditRetentionPolicy $retentionPolicy = null;

    public function __construct()
    {
        $this->auditChangeSets = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

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

    public function getObjectId(): ?int
    {
        return $this->object_id;
    }

    public function setObjectId(int $object_id): static
    {
        $this->object_id = $object_id;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): static
    {
        $this->ip = $ip;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->user_agent;
    }

    public function setUserAgent(?string $user_agent): static
    {
        $this->user_agent = $user_agent;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function getAction(): ?AuditActionEnum
    {
        return $this->action;
    }

    public function setAction(AuditActionEnum $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getSeverity(): ?AuditSeverityEnum
    {
        return $this->severity;
    }

    public function setSeverity(AuditSeverityEnum $severity): static
    {
        $this->severity = $severity;

        return $this;
    }

    /**
     * @return Collection<int, AuditChangeSet>
     */
    public function getAuditChangeSets(): Collection
    {
        return $this->auditChangeSets;
    }

    public function addAuditChangeSet(AuditChangeSet $auditChangeSet): static
    {
        if (!$this->auditChangeSets->contains($auditChangeSet)) {
            $this->auditChangeSets->add($auditChangeSet);
            $auditChangeSet->setEvent($this);
        }

        return $this;
    }

    public function removeAuditChangeSet(AuditChangeSet $auditChangeSet): static
    {
        if ($this->auditChangeSets->removeElement($auditChangeSet)) {
            // set the owning side to null (unless already changed)
            if ($auditChangeSet->getEvent() === $this) {
                $auditChangeSet->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AuditTag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(AuditTag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->setEvent($this);
        }

        return $this;
    }

    public function removeTag(AuditTag $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            // set the owning side to null (unless already changed)
            if ($tag->getEvent() === $this) {
                $tag->setEvent(null);
            }
        }

        return $this;
    }

    public function getActor(): ?AuditActor
    {
        return $this->actor;
    }

    public function setActor(?AuditActor $actor): static
    {
        $this->actor = $actor;

        return $this;
    }

    public function getRetentionPolicy(): ?AuditRetentionPolicy
    {
        return $this->retentionPolicy;
    }

    public function setRetentionPolicy(?AuditRetentionPolicy $retentionPolicy): static
    {
        $this->retentionPolicy = $retentionPolicy;

        return $this;
    }
}

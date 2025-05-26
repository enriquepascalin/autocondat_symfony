<?php

namespace App\Entity\ProjectModule;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\MultitenancyModule\Tenant;
use App\Entity\ProjectModule\DocumentCategoryEnum;
use App\Entity\ProjectModule\Project;
use App\Entity\StorageManagementModule\Document;
use App\Repository\ProjectDocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ProjectDocumentRepository::class)]
#[ApiResource]
#[Broadcast]
class ProjectDocument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'projectDocuments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tenant $tenant = null;

    #[ORM\ManyToOne(inversedBy: 'projectDocuments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\Column(enumType: DocumentCategoryEnum::class)]
    private ?DocumentCategoryEnum $category = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Document $document = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getCategory(): ?DocumentCategoryEnum
    {
        return $this->category;
    }

    public function setCategory(DocumentCategoryEnum $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): static
    {
        $this->document = $document;

        return $this;
    }
}

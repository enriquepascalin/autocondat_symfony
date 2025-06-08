<?php

declare(strict_types=1);

namespace App\Entity\AuthenticationModule;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AuthenticationModule\PermissionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
#[ApiResource]
#[Broadcast]
class Permission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(enumType: ScopeEnum::class)]
    private ?ScopeEnum $scope = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getScope(): ?ScopeEnum
    {
        return $this->scope;
    }

    public function setScope(ScopeEnum $scope): static
    {
        $this->scope = $scope;

        return $this;
    }
}

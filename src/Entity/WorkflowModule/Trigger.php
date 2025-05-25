<?php

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

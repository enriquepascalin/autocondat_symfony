<?php

namespace App\Entity\NotificationModule;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\NotificationModule\ScheduleRuleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ScheduleRuleRepository::class)]
#[ApiResource]
#[Broadcast]
class ScheduleRule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $cronExpression = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $excludeDates = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $timezone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCronExpression(): ?string
    {
        return $this->cronExpression;
    }

    public function setCronExpression(string $cronExpression): static
    {
        $this->cronExpression = $cronExpression;

        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getExcludeDates(): ?array
    {
        return $this->excludeDates;
    }

    public function setExcludeDates(?array $excludeDates): static
    {
        $this->excludeDates = $excludeDates;

        return $this;
    }

    public function getTimezone(): ?array
    {
        return $this->timezone;
    }

    public function setTimezone(?array $timezone): static
    {
        $this->timezone = $timezone;

        return $this;
    }
}

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

namespace App\Entity\LocalizationModule;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LocalizationModule\TranslationEntryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use App\Contracts\BlameableInterface;
use App\Contracts\TimestampableInterface;
use App\Contracts\SoftDeletableInterface;
use App\Contracts\TenantAwareInterface;
use App\Traits\BlameableTrait;
use App\Traits\TimestampableTrait;
use App\Traits\SoftDeletableTrait;
use App\Traits\TenantAwareTrait;

#[ORM\Entity(repositoryClass: TranslationEntryRepository::class)]
#[ApiResource]
#[Broadcast]
class TranslationEntry implements TimestampableInterface, SoftDeletableInterface, TenantAwareInterface, BlameableInterface
{
    use TimestampableTrait;
    use SoftDeletableTrait;
    use TenantAwareTrait;
    use BlameableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $locale = null;

    #[ORM\Column(length: 255)]
    private ?string $domain = null;

    #[ORM\Column(length: 255)]
    private ?string $key = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $value = null;

    #[ORM\Column(enumType: TranslationSourceEnum::class, nullable: true)]
    private ?TranslationSourceEnum $source = null;

    #[ORM\Column]
    private ?bool $isOverride = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): static
    {
        $this->domain = $domain;

        return $this;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key): static
    {
        $this->key = $key;

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

    public function getSource(): ?TranslationSourceEnum
    {
        return $this->source;
    }

    public function setSource(?TranslationSourceEnum $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function isOverride(): ?bool
    {
        return $this->isOverride;
    }

    public function setIsOverride(bool $isOverride): static
    {
        $this->isOverride = $isOverride;

        return $this;
    }
}

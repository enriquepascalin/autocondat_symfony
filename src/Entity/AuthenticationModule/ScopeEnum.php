<?php

declare(strict_types=1);

namespace App\Entity\AuthenticationModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ScopeEnum: int implements TranslatableInterface
{
    case TENANT = 0; // Tenant-specific scope, used for multi-tenant applications.
    case SYSTEM = 1;  // System-wide scope, used for features that apply across all tenants.

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('scope.'.$this->name, [], 'enums', $locale);
    }
}


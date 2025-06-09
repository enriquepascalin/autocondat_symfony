<?php

declare(strict_types=1);

namespace App\Entity\AuthenticationModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum RolesEnum: int implements TranslatableInterface
{
    case SUPER_ADMIN = 0;       // Full access to all system features and settings.
    case TENANT_ADMIN = 1;      // Admin access for a specific tenant, can manage users and settings within that tenant.
    case TENANT_USER = 2;       // Regular user access within a tenant, can perform day-to-day tasks.
    case USER = 3;              // General user role, typically with limited access to features.

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('roles.'.$this->name, [], 'enums', $locale);
    }
}

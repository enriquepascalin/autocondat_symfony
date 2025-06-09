<?php

declare(strict_types=1);

namespace App\Entity\AuthenticationModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum UserStatusEnum: int implements TranslatableInterface
{
    case ACTIVE = 0;          // User is active and can log in.
    case INACTIVE = 1;        // User is inactive, cannot log in.
    case SUSPENDED = 2;       // User is suspended, cannot log in but may be reactivated.
    case DELETED = 3;         // User account is deleted, cannot log in and data may be purged..

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('user_status.'.$this->name, [], 'enums', $locale);
    }
}

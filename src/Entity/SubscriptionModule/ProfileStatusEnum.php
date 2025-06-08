<?php

declare(strict_types=1);

namespace App\Entity\SubscriptionModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ProfileStatusEnum: int implements TranslatableInterface
{
    case UNVERIFIED = 0; // Unverified
    case VERIFIED = 1;   // Verified
    case SUSPENDED = 2;  // Suspended

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('profile_status.'.$this->name, [], 'enums', $locale);
    }
}

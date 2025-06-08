<?php

declare(strict_types=1);

namespace App\Entity\SubscriptionModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum SubscriptionStatusEnum: int implements TranslatableInterface
{
    case ACTIVE = 0;     // Active
    case PENDING = 1;    // Pending
    case SUSPENDED = 2;  // Suspended
    case CANCELLED = 3;  // Cancelled

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('subscription_status.'.$this->name, [], 'enums', $locale);
    }
}

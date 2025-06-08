<?php

declare(strict_types=1);

namespace App\Entity\SubscriptionModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum PricingModelEnum: int implements TranslatableInterface
{
    case FREE = 0;           // Free
    case ONE_TIME = 1;       // One-time
    case SUBSCRIPTION = 2;   // Subscription
    case USAGE_BASED = 3;    // Usage-based
    case CREDIT_BASED = 4;   // Credit-based
    case PAY_AS_YOU_GO = 5;  // Pay as you go
    case CUSTOM = 6;         // Custom

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('pricing_model.'.$this->name, [], 'enums', $locale);
    }
}

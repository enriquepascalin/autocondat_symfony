<?php

declare(strict_types=1);

namespace App\Entity\SubscriptionModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum TransactionTypeEnum: int implements TranslatableInterface
{
    case PURCHASE = 0;      // Purchase
    case REFUND = 1;        // Refund
    case RENEWAL = 2;       // Renewal
    case CANCELLATION = 3;  // Cancellation
    case ADJUSTMENT = 4;    // Adjustment
    case UPGRADE = 5;       // Upgrade
    case DOWNGRADE = 6;     // Downgrade
    case TRIAL_START = 7;   // Trial start
    case TRIAL_END = 8;     // Trial end

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('transaction_type.'.$this->name, [], 'enums', $locale);
    }
}

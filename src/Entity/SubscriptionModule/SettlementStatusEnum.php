<?php

namespace App\Entity\SubscriptionModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum SettlementStatusEnum: int implements TranslatableInterface
{
    case PENDING = 0;             // Pending
    case COMPLETED = 1;           // Completed
    case FAILED = 2;              // Failed
    case REFUNDED = 3;            // Refunded
    case PARTIALLY_REFUNDED = 4;  // Partially refunded

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans('settlement_status.' . $this->name, [], 'enums', $locale);
    }
}
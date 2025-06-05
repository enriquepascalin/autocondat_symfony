<?php

namespace App\Entity\SubscriptionModule;

enum TransactionTypeEnum: string
{
    case PURCHASE = 'purchase';
    case REFUND = 'refund';
    case RENEWAL = 'renewal';
    case CANCELLATION = 'cancellation';
    case ADJUSTMENT = 'adjustment';
    case UPGRADE = 'upgrade';
    case DOWNGRADE = 'downgrade';
    case TRIAL_START = 'trial_start';
    case TRIAL_END = 'trial_end';
}
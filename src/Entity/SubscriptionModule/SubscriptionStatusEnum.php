<?php

namespace App\Entity\SubscriptionModule;

enum SubscriptionStatusEnum: string
{
    case ACTIVE = 'active';
    case PENDING = 'pending';
    case SUSPENDED = 'suspended';
    case CANCELLED = 'cancelled';
}
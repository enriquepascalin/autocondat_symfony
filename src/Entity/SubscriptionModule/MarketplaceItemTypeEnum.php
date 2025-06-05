<?php

namespace App\Entity\SubscriptionModule;

enum MarketplaceItemTypeEnum: string
{
    case FEATURE = 'feature';
    case SERVICE = 'service';
    case BUNDLE = 'bundle';
    case SUBSCRIPTION = 'subscription';
}
<?php

namespace App\Entity\SubscriptionModule;

enum BundleTypeEnum: string
{
    case PLAN = 'plan';
    case TIER = 'tier';
    case PACKAGE = 'package';
}
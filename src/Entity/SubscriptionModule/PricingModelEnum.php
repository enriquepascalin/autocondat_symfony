<?php

namespace App\Entity\SubscriptionModule;

enum PricingModelEnum: string
{
    case FREE = 'free';
    case ONE_TIME = 'one_time';
    case SUBSCRIPTION = 'subscription';
    case USAGE_BASED = 'usage_based';
    case CREDIT_BASED = 'credit_based';
    case PAY_AS_YOU_GO = 'pay_as_you_go';
    case CUSTOM = 'custom';
}
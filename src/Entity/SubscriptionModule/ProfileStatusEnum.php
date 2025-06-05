<?php

namespace App\Entity\SubscriptionModule;

enum ProfileStatusEnum: string
{
    case UNVERIFIED = 'unverified';
    case VERIFIED = 'verified';
    case SUSPENDED = 'suspended';
}
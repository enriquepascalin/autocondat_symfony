<?php

namespace App\Entity\SubscriptionModule;

enum ServiceTypeEnum: string
{
    case SUPPORT = 'support';
    case TRAINING = 'training';
    case CONSULTING = 'consulting';
    case DEVELOPMENT = 'development';
    case MAINTENANCE = 'maintenance';
    case INFRASTRUCTURE = 'infrastructure';
    case OTHER = 'other';
}
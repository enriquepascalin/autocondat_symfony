<?php

namespace App\Entity\SubscriptionModule;

enum FeatureTypeEnum: string
{
    case MODULE = 'module';
    case SUBMODULE = 'sub_module';
    case FUNCTION = 'function';
    case INFRASTRUCTURE = 'infrastructure';
    case INTEGRATION = 'integration';
    case CUSTOM = 'custom';
    case OTHER = 'other';
}
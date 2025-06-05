<?php

namespace App\Entity\SubscriptionModule;

enum BundleStatusEnum: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case ARCHIVED = 'archived';
    case DELETED = 'deleted';
}
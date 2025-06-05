<?php

namespace App\Entity\SubscriptionModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum MarketplaceItemTypeEnum: int implements TranslatableInterface
{
    case FEATURE = 0;      // Feature
    case SERVICE = 1;      // Service
    case BUNDLE = 2;       // Bundle
    case SUBSCRIPTION = 3; // Subscription

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans('marketplace_item_type.' . $this->name, [], 'enums', $locale);
    }
}
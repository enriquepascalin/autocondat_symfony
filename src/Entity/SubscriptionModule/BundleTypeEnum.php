<?php

declare(strict_types=1);

namespace App\Entity\SubscriptionModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum BundleTypeEnum: int implements TranslatableInterface
{
    case PLAN = 0;    // Plan
    case TIER = 1;    // Tier
    case PACKAGE = 2; // Package

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('bundle_type.'.$this->name, [], 'enums', $locale);
    }
}

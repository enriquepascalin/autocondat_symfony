<?php

declare(strict_types=1);

namespace App\Entity\SubscriptionModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum FeatureTypeEnum: int implements TranslatableInterface
{
    case MODULE = 0;         // Module
    case SUBMODULE = 1;      // Submodule
    case FUNCTION = 2;       // Function
    case INFRASTRUCTURE = 3; // Infrastructure
    case INTEGRATION = 4;    // Integration
    case CUSTOM = 5;         // Custom
    case OTHER = 6;          // Other

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('feature_type.'.$this->name, [], 'enums', $locale);
    }
}

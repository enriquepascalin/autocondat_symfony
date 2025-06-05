<?php

namespace App\Entity\SubscriptionModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ServiceTypeEnum: int implements TranslatableInterface
{
    case SUPPORT = 0;        // Support
    case TRAINING = 1;       // Training
    case CONSULTING = 2;     // Consulting
    case DEVELOPMENT = 3;    // Development
    case MAINTENANCE = 4;    // Maintenance
    case INFRASTRUCTURE = 5; // Infrastructure
    case OTHER = 6;          // Other

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans('service_type.' . $this->name, [], 'enums', $locale);
    }
}
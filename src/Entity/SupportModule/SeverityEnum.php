<?php

declare(strict_types=1);

namespace App\Entity\SupportModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum SeverityEnum: int implements TranslatableInterface
{
    case LOW = 0;        // Low
    case MEDIUM = 1;     // Medium
    case HIGH = 2;       // High
    case CRITICAL = 3;   // Critical
    case EMERGENCY = 4;  // Emergency
    case BLOCKER = 5;    // Blocker

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('severity.'.$this->name, [], 'enums', $locale);
    }
}

<?php

declare(strict_types=1);

namespace App\Entity\LocalizationModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum TranslationSourceEnum: int implements TranslatableInterface
{
    case MANUAL = 0;     // Created manually by user
    case AUTO = 1;       // Generated via machine translation
    case IMPORTED = 2;   // Imported via file or external sync

    /**
     * Returns the translated string for the current translation source.
     */
    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('translation_source.'.$this->name, [], 'enums', $locale);
    }
}


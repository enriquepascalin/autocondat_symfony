<?php

namespace App\Entity\ProjectModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum DocumentCategoryEnum: int implements TranslatableInterface
{
    case CONTRACT = 0; // Contract
    case REPORT = 1;   // Report
    case DESIGN = 2;   // Design
    case OTHER = 3;    // Other

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans('document_category.' . $this->name, [], 'enums', $locale);
    }
}
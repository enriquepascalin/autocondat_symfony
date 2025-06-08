<?php

declare(strict_types=1);

namespace App\Entity\WorkflowModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum DeadlineTypeEnum: int implements TranslatableInterface
{
    case FIXED = 0;    // Fixed
    case RELATIVE = 1; // Relative

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('deadline_type.'.$this->name, [], 'enums', $locale);
    }
}

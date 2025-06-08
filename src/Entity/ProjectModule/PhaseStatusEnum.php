<?php

declare(strict_types=1);

namespace App\Entity\ProjectModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum PhaseStatusEnum: int implements TranslatableInterface
{
    case NOT_STARTED = 0; // Not started
    case IN_PROGRESS = 1; // In progress
    case COMPLETED = 2;   // Completed
    case BLOCKED = 3;     // Blocked

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('phase_status.'.$this->name, [], 'enums', $locale);
    }
}

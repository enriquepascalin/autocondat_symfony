<?php

namespace App\Entity\ProjectModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum PhaseTypeEnum: int implements TranslatableInterface
{
    case MILESTONE = 0;   // Milestone
    case SPRINT = 1;      // Sprint
    case TASK_GROUP = 2;  // Task group
    case DELIVERABLE = 3; // Deliverable

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans('phase_type.' . $this->name, [], 'enums', $locale);
    }
}
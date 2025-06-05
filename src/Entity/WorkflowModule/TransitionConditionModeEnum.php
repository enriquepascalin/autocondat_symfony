<?php

namespace App\Entity\WorkflowModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum TransitionConditionModeEnum: int implements TranslatableInterface
{
    case ALL = 0; // All
    case ANY = 1; // Any

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans('transition_condition_mode.' . $this->name, [], 'enums', $locale);
    }
}
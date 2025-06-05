<?php

namespace App\Entity\WorkflowModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum TriggerTypeEnum: int implements TranslatableInterface
{
    case EVENT = 0;    // Event
    case SCHEDULE = 1; // Schedule

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans('trigger_type.' . $this->name, [], 'enums', $locale);
    }
}
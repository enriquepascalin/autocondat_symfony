<?php

declare(strict_types=1);

namespace App\Entity\WorkflowModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum RecurranceRuleFrequencyEnum: int implements TranslatableInterface
{
    case STATE_CHANGE = 0;   // State change
    case TASK_ASSIGNED = 1;  // Task assigned
    case RULE_TRIGGERED = 2; // Rule triggered

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('recurrance_rule_frequency.'.$this->name, [], 'enums', $locale);
    }
}

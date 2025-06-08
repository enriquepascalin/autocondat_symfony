<?php

declare(strict_types=1);

namespace App\Entity\WorkflowModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum TaskPriorityEnum: int implements TranslatableInterface
{
    case LOW = 0;    // Low
    case MEDIUM = 1; // Medium
    case HIGH = 2;   // High

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('task_priority.'.$this->name, [], 'enums', $locale);
    }
}

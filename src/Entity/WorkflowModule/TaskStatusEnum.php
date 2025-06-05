<?php

namespace App\Entity\WorkflowModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum TaskStatusEnum: int implements TranslatableInterface
{
    case PENDING = 0;      // Pending
    case IN_PROGRESS = 1;  // In progress
    case COMPLETED = 2;    // Completed
    case OVERDUE = 3;      // Overdue

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans('task_status.' . $this->name, [], 'enums', $locale);
    }
}
<?php

declare(strict_types=1);

namespace App\Entity\WorkflowModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum WorkflowExecutionStatusEnum: int implements TranslatableInterface
{
    case ACTIVE = 0;     // Active
    case COMPLETED = 1;  // Completed
    case FAILED = 2;     // Failed

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('workflow_execution_status.'.$this->name, [], 'enums', $locale);
    }
}

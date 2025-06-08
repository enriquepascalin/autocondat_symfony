<?php

declare(strict_types=1);

namespace App\Entity\WorkflowModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum StateTypeEnum: int implements TranslatableInterface
{
    case START = 0;         // Start
    case INTERMEDIATE = 1;  // Intermediate
    case END = 2;           // End

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('state_type.'.$this->name, [], 'enums', $locale);
    }
}

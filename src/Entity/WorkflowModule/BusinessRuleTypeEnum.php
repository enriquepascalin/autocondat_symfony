<?php

namespace App\Entity\WorkflowModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum BusinessRuleTypeEnum: int implements TranslatableInterface
{
    case TIME = 0;   // Time
    case EVENT = 1;  // Event
    case DATA = 2;   // Data

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans('business_rule_type.' . $this->name, [], 'enums', $locale);
    }
}
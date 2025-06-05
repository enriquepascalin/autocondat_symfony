<?php

namespace App\Entity\WorkflowModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ConditionTypeEnum: int implements TranslatableInterface
{
    case EXPRESSION = 0; // Expression
    case API = 1;        // API

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans('condition_type.' . $this->name, [], 'enums', $locale);
    }
}
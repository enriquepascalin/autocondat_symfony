<?php

declare(strict_types=1);

namespace App\Entity\WorkflowModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ActorTypeEnum: int implements TranslatableInterface
{
    case USER = 0;    // User
    case SYSTEM = 1;  // System
    case API = 2;     // API

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('actor_type.'.$this->name, [], 'enums', $locale);
    }
}

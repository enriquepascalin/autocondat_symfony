<?php

namespace App\Entity\ProjectModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ProjectTypeEnum: int implements TranslatableInterface
{
    case SCRUM = 0;      // Scrum
    case KANBAN = 1;     // Kanban
    case WATERFALL = 2;  // Waterfall
    case CUSTOM = 3;     // Custom

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans('project_type.' . $this->name, [], 'enums', $locale);
    }
}
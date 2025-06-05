<?php

namespace App\Entity\ProjectModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum AssignmentRoleEnum: int implements TranslatableInterface
{
    case OWNER = 0;        // Owner
    case CONTRIBUTOR = 1;  // Contributor
    case REVIEWER = 2;     // Reviewer

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans('assignment_role.' . $this->name, [], 'enums', $locale);
    }
}
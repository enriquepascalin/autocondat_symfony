<?php

namespace App\Entity\ProjectModule;

enum AssignmentRoleEnum: string
{
    case OWNER = 'owner';
    case CONTRIBUTOR = 'contributor';
    case REVIEWER = 'reviewer';
}
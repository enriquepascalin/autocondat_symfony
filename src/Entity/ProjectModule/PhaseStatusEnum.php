<?php

namespace App\Entity\ProjectModule;

enum PhaseStatusEnum: string
{
    case NOT_STARTED = 'not_started';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case BLOCKED = 'blocked';
}
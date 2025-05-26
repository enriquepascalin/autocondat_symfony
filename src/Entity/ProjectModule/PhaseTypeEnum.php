<?php

namespace App\Entity\ProjectModule;

enum PhaseTypeEnum: string
{
    case MILESTONE = 'milestone';
    case SPRINT = 'sprint';
    case TASK_GROUP = 'task_group';
    case DELIVERABLE = 'deliverable';
}
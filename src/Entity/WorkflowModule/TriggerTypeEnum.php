<?php

namespace App\Entity\WorkflowModule;

enum TriggerTypeEnum: string
{
    case EVENT = 'event';
    case SCHEDULE = 'schedule';
}
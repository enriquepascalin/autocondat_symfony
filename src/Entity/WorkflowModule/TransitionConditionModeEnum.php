<?php

namespace App\Entity\WorkflowModule;

enum TransitionConditionModeEnum: string
{
    case ALL = 'all';
    case ANY = 'any';
}
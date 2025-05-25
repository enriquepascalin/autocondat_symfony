<?php

namespace App\Entity\WorkflowModule;

enum RecurranceRuleFrequencyEnum: string
{
    case STATE_CHANGE = 'state_change';
    case TASK_ASSIGNED = 'task_assigned';
    case RULE_TRIGGERED = 'rule_triggered';
}
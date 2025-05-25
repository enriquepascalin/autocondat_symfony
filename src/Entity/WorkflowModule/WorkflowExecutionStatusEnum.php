<?php

namespace App\Entity\WorkflowModule;

enum WorkflowExecutionStatusEnum: string
{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}
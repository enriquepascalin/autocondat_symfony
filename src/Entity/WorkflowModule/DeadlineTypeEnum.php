<?php

namespace App\Entity\WorkflowModule;

enum DeadlineTypeEnum: string
{
    case FIXED = 'fixed';
    case RELATIVE = 'relative';
}
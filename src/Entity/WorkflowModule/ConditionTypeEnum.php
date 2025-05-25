<?php

namespace App\Entity\WorkflowModule;

enum ConditionTypeEnum: string
{
    case EXPRESSION = 'expression';
    case API = 'api';
}
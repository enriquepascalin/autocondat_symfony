<?php

namespace App\Entity\WorkflowModule;

enum BusinessRuleTypeEnum: string
{
    case TIME = 'time';
    case EVENT = 'event';
    case DATA = 'data';
}
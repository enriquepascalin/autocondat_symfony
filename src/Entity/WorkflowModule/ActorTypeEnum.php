<?php

namespace App\Entity\WorkflowModule;

enum ActorTypeEnum: string
{
    case USER = 'user';
    case SYSTEM = 'system';
    case API = 'api';
}
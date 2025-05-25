<?php

namespace App\Entity\WorkflowModule;

enum StateTypeEnum: string
{
    case START = 'start';
    case INTERMEDIATE = 'intermediate';
    case END = 'end';
}
<?php

namespace App\Entity\ProjectModule;

enum DocumentCategoryEnum: string
{
    case CONTRACT = 'contract';
    case REPORT = 'report';
    case DESIGN = 'design';
    case OTHER = 'other';
}
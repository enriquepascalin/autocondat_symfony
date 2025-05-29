<?php

namespace App\Entity\SupportModule;

enum SeverityEnum: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';
    case EMERGENCY = 'emergency';
    case BLOCKER = 'blocker';    
}
<?php

declare(strict_types=1);

namespace App\Entity\AuthenticationModule;

enum ScopeEnum: string
{
    case TENANT = 'tenant';
    case SYSTEM = 'system';
}

<?php

namespace App\Entity\AuthenticationModule;

enum ScopeEnum: string
{
    case TENANT = 'tenant';
    case SYSTEM = 'system';
}
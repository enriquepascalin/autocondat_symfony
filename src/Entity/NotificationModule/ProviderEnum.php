<?php

namespace App\Entity\NotificationModule;

enum ProviderEnum: string
{
    case SYMFONY_MAILER = 'symfony_mailer';
    case TWILIO = 'twilio';
    case CUSTOM_API = 'custom_api';
}
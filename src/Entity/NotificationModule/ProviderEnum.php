<?php

namespace App\Entity\NotificationModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ProviderEnum: int implements TranslatableInterface
{
    case SYMFONY_MAILER = 0; // Symfony Mailer
    case TWILIO = 1;         // Twilio
    case CUSTOM_API = 2;     // Custom API

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans('provider.' . $this->name, [], 'enums', $locale);
    }
}
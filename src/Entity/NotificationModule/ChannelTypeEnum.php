<?php

namespace App\Entity\NotificationModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ChannelTypeEnum: int implements TranslatableInterface
{
    case EMAIL = 0; // Email notifications.
    case SMS = 1; // SMS notifications.
    case IN_APP = 2; // Uses Symfony Mercure or WebSocket.
    case SLACK =  3; // Slack notifications.
    case PUSH = 4;     // Mobile push notifications.
    case WEBHOOK = 5; // Webhook notifications.

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans('channel_type.' . $this->name, [], 'enums', $locale);
    }
}
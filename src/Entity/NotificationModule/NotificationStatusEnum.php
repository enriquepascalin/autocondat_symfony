<?php

namespace App\Entity\NotificationModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum NotificationStatusEnum: int implements TranslatableInterface
{
    case DRAFT = 0;         // Notification is being edited.
    case SCHEDULED = 1;     // Queued for future delivery.
    case SENT = 2;          // Successfully dispatched to channels.
    case FAILED = 3;        // Delivery failed across all channels.

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans('notification_status.' . $this->name, [], 'enums', $locale);
    }
}
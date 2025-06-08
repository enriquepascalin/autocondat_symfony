<?php

declare(strict_types=1);

namespace App\Entity\NotificationModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum NotificationTypeEnum: int implements TranslatableInterface
{
    case ALERT = 0;           // Urgent system message (e.g., downtime).
    case REMINDER = 1;        // Time-based reminder (e.g., "Meeting in 15m").
    case SYSTEM = 2;          // General system update.
    case ACTION_REQUIRED = 3; // User must take action (e.g., sign a doc).

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('notification_type.'.$this->name, [], 'enums', $locale);
    }
}

<?php

namespace App\Entity\NotificationModule;

enum NotificationTypeEnum: string
{
    case ALERT = 'alert';          // Urgent system message (e.g., downtime).
    case REMINDER = 'reminder';    // Time-based reminder (e.g., "Meeting in 15m").
    case SYSTEM = 'system';        // General system update.
    case ACTION_REQUIRED = 'action_required'; // User must take action (e.g., sign a doc).
}
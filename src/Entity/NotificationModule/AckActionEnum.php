<?php

namespace App\Entity\NotificationModule;

enum AckActionEnum: string
{
    case DISMISSED = 'dismissed'; // Temporarily closed.
    case CONFIRMED = 'confirmed'; // User saw the notification.
    case AGREED = 'agreed';       // Accepted terms (e.g., legal docs).
}
<?php

namespace App\Entity\NotificationModule;

enum NotificationStatusEnum: string
{
    case DRAFT = 'draft';         // Notification is being edited.
    case SCHEDULED = 'scheduled'; // Queued for future delivery.
    case SENT = 'sent';           // Successfully dispatched to channels.
    case FAILED = 'failed';       // Delivery failed across all channels.
}
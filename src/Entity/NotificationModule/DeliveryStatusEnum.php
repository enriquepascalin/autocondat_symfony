<?php

namespace App\Entity\NotificationModule;

enum DeliveryStatusEnum: string
{
    case QUEUED = 'queued';
    case SENT = 'sent';
    case DELIVERED = 'delivered';
    case FAILED = 'failed';
}
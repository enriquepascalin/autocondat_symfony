<?php
/**
 * Copyright © 2025 Enrique Pascalin <erparom@gmail.com>
 * This source code is protected under international copyright law.
 * All rights reserved. No warranty, explicit or implicit, provided.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * This file is confidential and only available to authorized individuals with the
 * permission of the copyright holders.  If you encounter this file and do not have
 * permission, please contact the copyright holders and delete this file.
 *
 * @author Enrique Pascalin, Erparom Technologies
 *
 * @version 1.0.0
 *
 * @since 2025-06-01
 *
 * @license license.md
 */

declare(strict_types=1);

namespace App\NotificationModule\Event;

use App\NotificationModule\Entity\Notification;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Dispatched at key notification state changes
 */
final class NotificationLifecycleEvent extends Event
{
    public const CREATED = 'notification.created';
    public const PROCESSING = 'notification.processing';
    public const COMPLETED = 'notification.completed';
    public const FAILED = 'notification.failed';
    public const TRANSITION = 'notification.transition';
    public const QUEUED = 'notification.queued';
    public const SCHEDULED = 'notification.scheduled';
    public const CANCELLED = 'notification.cancelled';
    public const EXPIRED = 'notification.expired';
    public const RETRIED = 'notification.retried';
    public const SENT = 'notification.sent';
    public const DELIVERED = 'notification.delivered';
    public const ACKNOWLEDGED = 'notification.acknowledged';
    public const REJECTED = 'notification.rejected';
    public const OPENED = 'notification.opened';

    public function __construct(
        private Notification $notification,
        private string $transition
    ) {}

 
}
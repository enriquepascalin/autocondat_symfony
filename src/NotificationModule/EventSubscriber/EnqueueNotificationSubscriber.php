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

namespace App\NotificationModule\EventSubscriber;

use App\NotificationModule\Event\NotificationLifecycleEvent;
use App\NotificationModule\Message\SendNotificationMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class EnqueueNotificationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            NotificationLifecycleEvent::CREATED => 'onNotificationCreated',
        ];
    }

    /**
     * Enqueues notifications for asynchronous processing
     */
    public function onNotificationCreated(NotificationLifecycleEvent $event): void
    {
        $notification = $event->getNotification();
        
        $this->messageBus->dispatch(
            new SendNotificationMessage($notification->getId())
        );
    }
}
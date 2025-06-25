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
use App\NotificationModule\Service\NotificationCircuitBreaker;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

final class DeliveryRetrySubscriber implements EventSubscriberInterface
{
    private const MAX_RETRIES = 3;
    private const INITIAL_DELAY = 5000; // 5 seconds in milliseconds

    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly NotificationCircuitBreaker $circuitBreaker,
        private readonly LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            NotificationLifecycleEvent::FAILED => 'onNotificationFailed',
        ];
    }

    /**
     * Handles failed notifications with retry logic
     */
    public function onNotificationFailed(NotificationLifecycleEvent $event): void
    {
        $notification = $event->getNotification();
        
        if ($notification->getRetryCount() >= self::MAX_RETRIES) {
            $this->logger->warning('Max retries exceeded', [
                'notification' => $notification->getId()
            ]);
            return;
        }

        $retryDelay = self::INITIAL_DELAY * pow(2, $notification->getRetryCount());
        $notification->incrementRetryCount();
        
        $this->logger->info('Scheduling retry', [
            'notification' => $notification->getId(),
            'retry_count' => $notification->getRetryCount(),
            'delay_ms' => $retryDelay
        ]);
        
        $envelope = new Envelope(
            new SendNotificationMessage($notification->getId()),
            [new DelayStamp($retryDelay)]
        );
        
        $this->messageBus->dispatch($envelope);
    }
}
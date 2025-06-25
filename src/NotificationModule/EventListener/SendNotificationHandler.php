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

namespace App\NotificationModule\EventListener;

use App\NotificationModule\Event\NotificationRequestedEvent;
use App\NotificationModule\Service\DeliveryPipelineService;
use Psr\Log\LoggerInterface;

final class SendNotificationHandler
{
    public function __construct(
        private readonly DeliveryPipelineService $pipeline,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Handles notification delivery requests
     */
    public function __invoke(NotificationRequestedEvent $event): void
    {
        $notification = $event->getNotification();
        
        try {
            $this->logger->info('Processing notification', [
                'id' => $notification->getId(),
                'type' => $notification->getType()
            ]);
            
            $this->pipeline->execute($notification);
            
            $this->logger->info('Notification processed successfully', [
                'id' => $notification->getId()
            ]);
        } catch (\Throwable $e) {
            $this->logger->error('Notification processing failed', [
                'id' => $notification->getId(),
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
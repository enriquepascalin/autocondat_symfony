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

use App\NotificationModule\Event\CircuitBreakerStateChangedEvent;
use App\NotificationModule\Service\NotificationTrackerService;
use App\SupportModule\Service\SupportTicketServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Handles circuit breaker state change events for notification channels.
 *
 * This subscriber logs state transitions, creates support tickets for critical state changes,
 * and updates tracking metrics for channel health monitoring.
 */
final class CircuitBreakerStateChangedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly NotificationTrackerService $trackerService,
        private readonly SupportTicketServiceInterface $supportService
    ) {
    }

    /**
     * Defines the events this subscriber listens to.
     *
     * @return array<string, string> The event names to listen to and their corresponding methods
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CircuitBreakerStateChangedEvent::class => 'onCircuitBreakerStateChange',
        ];
    }

    /**
     * Processes circuit breaker state change events.
     *
     * @param CircuitBreakerStateChangedEvent $event The circuit breaker state change event
     *
     * @throws \Psr\Log\LogLevel
     */
    public function onCircuitBreakerStateChange(CircuitBreakerStateChangedEvent $event): void
    {
        $channel = $event->getChannel();
        $previousState = $event->getPreviousState();
        $newState = $event->getNewState();
        $tenant = $event->getTenant();

        // Log state transition
        $this->logger->warning(
            'Circuit breaker state changed for {channel} channel', 
            [
                'channel' => $channel->value,
                'previous' => $previousState,
                'new' => $newState,
                'tenant' => $tenant->getId(),
            ]
        );

        // Update tracking metrics
        $this->trackerService->recordCircuitBreakerChange(
            $channel,
            $previousState,
            $newState,
            $tenant
        );

        // Create support ticket for critical state changes
        if ($newState === 'open') {
            $this->supportService->createCircuitBreakerTicket( 
                $channel,
                $tenant,
                "Circuit breaker tripped for {$channel->value} channel"
            );
        }
    }
}
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

use App\Contracts\CircuitBreakerInterface;
use App\NotificationModule\Service\NotificationTrackerService;
use App\SupportModule\Contract\SupportTicketServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event as WorkflowEvent;

/**
 * Handles circuit breaker state transitions emitted by the Symfony state-machine.
 *
 * The subscriber listens to the generic `workflow.circuit_breaker.transition` event,
 * logs the transition, updates tracking metrics and optionally opens a support ticket
 * when the breaker trips to the OPEN state.
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
     * {@inheritdoc}
     *
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        // One hook for all transitions in the `circuit_breaker` state-machine
        return [
            'workflow.circuit_breaker.transition' => 'onCircuitBreakerTransition',
        ];
    }

    /**
     * Processes every state transition of the circuit breaker.
     *
     * @param WorkflowEvent $event Workflow transition event produced by the state-machine
     */
    public function onCircuitBreakerTransition(WorkflowEvent $event): void
    {
        /** @var CircuitBreakerInterface $cb */
        $cb            = $event->getSubject();
        $transition    = $event->getTransition();
        $previousState = implode(', ', array_keys($event->getMarking()->getPlaces()));
        $newState      = implode(', ', $transition->getTos());

        // Log state transition
        $this->logger->warning(
            'Circuit breaker state changed for {channel} channel',
            [
                'channel'  => $cb->getChannel()->value,
                'previous' => $previousState,
                'new'      => $newState,
                'tenant'   => $cb->getTenant()->getId(),
            ]
        );

        // Update tracking metrics
        $this->trackerService->recordCircuitBreakerChange(
            $cb->getChannel(),
            $previousState,
            $newState,
            $cb->getTenant()
        );

        // Create support ticket for critical state changes
        if ($newState === 'open') {
            $this->supportService->createCircuitBreakerTicket(
                $cb->getChannel(),
                $cb->getTenant(),
                "Circuit breaker tripped for {$cb->getChannel()->value} channel"
            );
        }
    }
}
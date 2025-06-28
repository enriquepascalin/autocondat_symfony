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

use App\NotificationModule\Entity\ChannelTypeEnum;
use App\MultitenancyModule\Entity\Tenant;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Dispatched when a notification channel's circuit breaker state changes.
 *
 * This event provides critical information about channel health state transitions
 * to enable system monitoring, alerting, and failover workflows.
 */
final class CircuitBreakerStateChangedEvent extends Event
{
    public function __construct(
        private readonly ChannelTypeEnum $channel,
        private readonly string $previousState,
        private readonly string $newState,
        private readonly Tenant $tenant
    ) {
    }

    /**
     * Get the channel type that changed state.
     * 
     * @return ChannelTypeEnum
     */
    public function getChannel(): ChannelTypeEnum
    {
        return $this->channel;
    }

    /**
     * Get the previous state of the channel.
     * 
     * @return string
     */
    public function getPreviousState(): string
    {
        return $this->previousState;
    }

    /**
     * Get the new state of the channel.
     * 
     * @return string
     */
    public function getNewState(): string
    {
        return $this->newState;
    }

    /**
     * Get the tenant associated with this event.
     * 
     * @return Tenant
     */
    public function getTenant(): Tenant
    {
        return $this->tenant;
    }
}
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

namespace App\Contracts;

/**
 * Circuit Breaker pattern contract
 */
interface CircuitBreakerInterface
{
    /** 
     * Checks if the circuit breaker is available for operation.
     */
    public function isAvailable(): bool;
    
    /** 
     * Records a successful operation, resetting failure count
     * and potentially closing the circuit if it was open.
     */
    public function recordSuccess(): void;

    /**
     * Records a failure, incrementing the failure count
     * and potentially opening the circuit if failure threshold is reached.
     */
    public function recordFailure(): void;

    /**
     * Gets the current state of the circuit breaker.
     * Possible states: 'closed', 'open', 'half-open'.
     */
    public function getState(): string;

    /**
     * Gets the number of consecutive failures recorded.
     * This can be used to determine if the circuit breaker should open.
     */
    public function getFailureCount(): int;

    /**
     * Resets the circuit breaker state and failure count.
     * This is typically called after a cooldown period or when the system is ready to retry operations.
     */
    public function reset(): void;
}
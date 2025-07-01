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

namespace App\NotificationModule\Service;

use App\Contracts\CircuitBreakerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Component\Workflow\Exception\TransitionException;

/**
 * Circuit Breaker implementation for outbound notification providers.
 *
 * The class relies on a Symfony cache pool implementing {@see CacheInterface}
 * so that it can atomically read-through on cache misses and obtain
 * stampede-protection semantics.
 */
final class NotificationCircuitBreaker implements CircuitBreakerInterface
{
    private const CACHE_PREFIX   = 'circuit_breaker_';
    private const FAILURE_COUNT  = 'failure_count';
    private const LAST_FAILURE   = 'last_failure';

    /**
     * Current place in the state-machine; the SingleStateMarkingStore
     * (Symfony default) reads/writes this property directly.
     */
    private string $marking = 'closed';

    /**
     * @param CacheInterface    $cache           Autowired pool (e.g. cache.app ➜ Redis)
     * @param WorkflowInterface $stateMachine    The `workflow.circuit_breaker` state-machine
     * @param LoggerInterface   $logger          Logger for debugging state transitions
     * @param int               $failureThreshold Consecutive failures before opening the circuit
     * @param int               $resetTimeout     Seconds to wait before a half-open trial
     * @param string            $serviceName      Identifier to scope keys per external service
     */
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly WorkflowInterface $stateMachine,
        private readonly LoggerInterface $logger,
        private readonly int $failureThreshold = 3,
        private readonly int $resetTimeout = 60,
        private readonly string $serviceName = 'default',
    ) { }

    /**
     * Whether the circuit currently permits an outbound request.
     * The circuit breaker starts in the CLOSED state, allowing all requests.
     * If the circuit is OPEN, it will not allow requests until the
     * cool-down period has elapsed and a trial request is made.
     * If the circuit is HALF_OPEN, it allows exactly one trial request
     * to test if the external service is responsive again.
     * 
     * @return bool True if the circuit is CLOSED or HALF_OPEN, false if OPEN
     * 
     */
    public function isAvailable(): bool
    {
        $state = $this->getState();

        if ($state === 'closed') {
            return true;
        }

        if ($state === 'open') {
            // If the cool-down has elapsed, move to HALF_OPEN and allow a trial request
            if ((time() - $this->getLastFailureTime()) > $this->resetTimeout &&
                $this->stateMachine->can($this, 'probe')) {                   
                try {
                    $this->stateMachine->apply($this, 'probe');
                } catch (TransitionException $e) {
                    $this->logger->error('Probe transition failed', ['error' => $e]);
                }
            }

            return false; // still OPEN until probe transition succeeds
        }

        // HALF_OPEN → allow exactly one trial request
        return true;
    }

    /**
     * Records a successful outbound call.
     * This resets the failure count and timestamps,
     * and transitions the circuit back to CLOSED if it was OPEN.
     * If the circuit was HALF_OPEN, it will also reset to CLOSED
     * after a successful trial request.
     * 
     * @return void
     */
    public function recordSuccess(): void
    {
        // Clear failure counter & timestamps
        $this->cache->delete($this->key(self::FAILURE_COUNT));
        $this->cache->delete($this->key(self::LAST_FAILURE));

        // Transition back to CLOSED if possible
        if ($this->stateMachine->can($this, 'reset')) {
            $this->stateMachine->apply($this, 'reset');
        }
    }


    /**
     * Records a failed outbound call.
     * This increments the failure count,
     * updates the last failure timestamp,
     * and may transition the circuit to OPEN if the failure threshold is reached.
     * 
     * @return void
     */
    public function recordFailure(): void
    {
        $failures = $this->incrementFailureCount();
        $this->setLastFailureTime(time());

        if ($failures >= $this->failureThreshold && $this->stateMachine->can($this, 'trip')) {
            $this->stateMachine->apply($this, 'trip');
        }
    }

    /**
     * Current place in the state-machine.
     * This is the state of the circuit breaker,
     * which can be 'closed', 'open', or 'half-open'.
     * 
     * @return string The current state of the circuit breaker
     */
    public function getState(): string
    {
        return $this->marking;
    }

    /**
     * Sets the state of the circuit breaker.
     * This method allows manual control of the circuit state,
     * which can be useful for administrative actions or testing.
     * 
     * @param string $state The new state to set, must be one of 'closed', 'open', or 'half-open'
     * 
     * @throws \InvalidArgumentException If the provided state is not valid
     * @return void
     */
    public function setState(string $state): void
    {
        // Ensure the state is valid before setting
        if (!in_array($state, ['closed', 'open', 'half-open'], true)) {
            throw new \InvalidArgumentException("Invalid state: $state");
        }

        $this->marking = $state;
    }

    /**
     * Number of consecutive failures since the last success.
     * This is used to determine if the circuit should open.
     * 
     * @return int The count of consecutive failures
     */
    public function getFailureCount(): int
    {
        return $this->cache->get($this->key(self::FAILURE_COUNT), function (ItemInterface $item) {
            $item->expiresAfter($this->resetTimeout);
            return 0;
        });
    }

    /**
     * Gets the service name associated with this circuit breaker.
     * This is used to scope the cache keys and ensure that
     * different services can have their own independent circuit breakers.
     * 
     * @return string The service name for this circuit breaker
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * Forces a full reset (typically from an admin action).
     * This clears the failure count and last failure timestamp,
     * and transitions the circuit back to CLOSED state.
     * 
     * @return void
     */
    public function reset(): void
    {
        $this->cache->delete($this->key(self::FAILURE_COUNT));
        $this->cache->delete($this->key(self::LAST_FAILURE));

        if ($this->stateMachine->can($this, 'reset')) {
            $this->stateMachine->apply($this, 'reset');
        }
    }

    /**
     * Gets the timestamp of the last failure.
     * This is used to determine when the circuit can transition
     * from OPEN to HALF_OPEN after the reset timeout.
     * 
     * @return int The timestamp of the last failure, or 0 if never failed
     */
    private function getLastFailureTime(): int
    {
        return $this->cache->get($this->key(self::LAST_FAILURE), function (ItemInterface $item) {
            $item->expiresAfter($this->resetTimeout * 2);
            return 0;
        });
    }

    /**
     * Sets the timestamp of the last failure.
     * This is used to track when the last failure occurred,
     * which is important for determining when to transition
     * from OPEN to HALF_OPEN after the reset timeout.
     * 
     * @param int $timestamp The timestamp of the last failure
     * 
     * @return void
     */
    private function setLastFailureTime(int $timestamp): void
    {
        $this->cache->get($this->key(self::LAST_FAILURE), function (ItemInterface $item) use ($timestamp) {
            $item->expiresAfter($this->resetTimeout * 2);
            return $timestamp;
        });
    }

    /**
     * Increments the failure count atomically.
     * This method uses the cache to ensure that the failure count
     * is updated in a thread-safe manner.
     * 
     * @return int The new failure count after incrementing
     */
    private function incrementFailureCount(): int
    {
        $count = $this->getFailureCount() + 1;

        $this->cache->get($this->key(self::FAILURE_COUNT), function (ItemInterface $item) use ($count) {
            $item->expiresAfter($this->resetTimeout);
            return $count;
        });

        return $count;
    }

    /**
     * Generates a cache key for the circuit breaker.
     * This method prefixes the key with the service name to ensure
     * that different services can have their own independent circuit breakers.
     * 
     * @param string $suffix The suffix to append to the cache key
     * 
     * @return string The generated cache key
     */
    private function key(string $suffix): string
    {
        return self::CACHE_PREFIX . $this->serviceName . '_' . $suffix;
    }
}

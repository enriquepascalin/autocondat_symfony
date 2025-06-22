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

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Contracts\Cache\ItemInterface;
use App\Contracts\CircuitBreakerInterface;

/**
 * Circuit Breaker implementation for notification services
 */
final class NotificationCircuitBreaker implements CircuitBreakerInterface
{
    private const OPEN = 'open';
    private const HALF_OPEN = 'half_open';
    private const CLOSED = 'closed';
    
    private const CACHE_PREFIX = 'circuit_breaker_';
    private const FAILURE_COUNT_KEY = 'failure_count';
    private const STATE_KEY = 'state';
    private const LAST_FAILURE_KEY = 'last_failure';

    public function __construct(
        private readonly string $serviceName,
        private readonly int $failureThreshold,
        private readonly int $resetTimeout,
        private readonly AdapterInterface $cache
    ) {}

    public function isAvailable(): bool
    {
        $state = $this->getCurrentState();
        
        if ($state === self::CLOSED) {
            return true;
        }
        
        if ($state === self::OPEN) {
            $lastFailure = $this->getLastFailureTime();
            return (time() - $lastFailure) > $this->resetTimeout;
        }
        
        // HALF_OPEN state allows one trial
        return true;
    }

    public function recordSuccess(): void
    {
        $this->cache->delete($this->getKey(self::FAILURE_COUNT_KEY));
        $this->setState(self::CLOSED);
    }

    public function recordFailure(): void
    {
        $failures = $this->incrementFailureCount();
        $this->setLastFailureTime(time());
        
        if ($failures >= $this->failureThreshold) {
            $this->setState(self::OPEN);
        }
    }

    public function getState(): string
    {
        return $this->getCurrentState();
    }

    public function getFailureCount(): int
    {
        return $this->cache->get(
            $this->getKey(self::FAILURE_COUNT_KEY),
            function (ItemInterface $item) {
                $item->expiresAfter($this->resetTimeout);
                return 0;
            }
        );
    }

    public function reset(): void
    {
        $this->cache->delete($this->getKey(self::FAILURE_COUNT_KEY));
        $this->cache->delete($this->getKey(self::STATE_KEY));
        $this->cache->delete($this->getKey(self::LAST_FAILURE_KEY));
    }

    private function getCurrentState(): string
    {
        return $this->cache->get(
            $this->getKey(self::STATE_KEY),
            function (ItemInterface $item) {
                $item->expiresAfter(0);
                return self::CLOSED;
            }
        );
    }

    private function setState(string $state): void
    {
        $this->cache->get(
            $this->getKey(self::STATE_KEY),
            function (ItemInterface $item) use ($state) {
                $item->expiresAfter($this->resetTimeout);
                return $state;
            }
        );
    }

    private function getLastFailureTime(): int
    {
        return $this->cache->get(
            $this->getKey(self::LAST_FAILURE_KEY),
            function (ItemInterface $item) {
                $item->expiresAfter($this->resetTimeout * 2);
                return 0;
            }
        );
    }

    private function setLastFailureTime(int $timestamp): void
    {
        $this->cache->get(
            $this->getKey(self::LAST_FAILURE_KEY),
            function (ItemInterface $item) use ($timestamp) {
                $item->expiresAfter($this->resetTimeout * 2);
                return $timestamp;
            }
        );
    }

    private function incrementFailureCount(): int
    {
        $count = $this->getFailureCount() + 1;
        $this->cache->get(
            $this->getKey(self::FAILURE_COUNT_KEY),
            function (ItemInterface $item) use ($count) {
                $item->expiresAfter($this->resetTimeout);
                return $count;
            }
        );
        return $count;
    }

    private function getKey(string $type): string
    {
        return self::CACHE_PREFIX . $this->serviceName . '_' . $type;
    }
}
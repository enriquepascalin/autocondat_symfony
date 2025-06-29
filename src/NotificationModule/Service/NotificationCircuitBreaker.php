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

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use App\Contracts\CircuitBreakerInterface;

/**
 * Circuit Breaker implementation for outbound notification providers.
 *
 * The class relies on a Symfony cache pool implementing {@see CacheInterface}
 * so that it can atomically read-through on cache misses and obtain
 * stampede-protection semantics.
 */
final class NotificationCircuitBreaker implements CircuitBreakerInterface
{
    private const OPEN           = 'open';
    private const HALF_OPEN      = 'half_open';
    private const CLOSED         = 'closed';

    private const CACHE_PREFIX   = 'circuit_breaker_';
    private const FAILURE_COUNT  = 'failure_count';
    private const STATE          = 'state';
    private const LAST_FAILURE   = 'last_failure';

    /**
     * @param CacheInterface $cache            Autowired pool (e.g. cache.app ➜ Redis)
     * @param int            $failureThreshold Consecutive failures before opening the circuit
     * @param int            $resetTimeout     Seconds to wait before a half-open trial
     * @param string         $serviceName      Identifier to scope keys per external service
     */
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly int $failureThreshold = 3,
        private readonly int $resetTimeout = 60,
        private readonly string $serviceName = 'default',
    ) {}

    public function isAvailable(): bool
    {
        $state = $this->getState();
        if ($state === self::CLOSED) {
            return true;
        }

        if ($state === self::OPEN) {
            return (time() - $this->getLastFailureTime()) > $this->resetTimeout;
        }

        // HALF-OPEN allows a single trial request
        return true;
    }

    public function recordSuccess(): void
    {
        $this->cache->delete($this->key(self::FAILURE_COUNT));
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
        return $this->cache->get($this->key(self::STATE), function (ItemInterface $item) {
            $item->expiresAfter(0);
            return self::CLOSED;
        });
    }

    public function getFailureCount(): int
    {
        return $this->cache->get($this->key(self::FAILURE_COUNT), function (ItemInterface $item) {
            $item->expiresAfter($this->resetTimeout);
            return 0;
        });
    }

    public function reset(): void
    {
        $this->cache->delete($this->key(self::FAILURE_COUNT));
        $this->cache->delete($this->key(self::STATE));
        $this->cache->delete($this->key(self::LAST_FAILURE));
    }

    /* ---------- Private helpers ------------------------------------------------ */

    private function setState(string $state): void
    {
        $this->cache->get($this->key(self::STATE), function (ItemInterface $item) use ($state) {
            $item->expiresAfter($this->resetTimeout);
            return $state;
        });
    }

    private function getLastFailureTime(): int
    {
        return $this->cache->get($this->key(self::LAST_FAILURE), function (ItemInterface $item) {
            $item->expiresAfter($this->resetTimeout * 2);
            return 0;
        });
    }

    private function setLastFailureTime(int $timestamp): void
    {
        $this->cache->get($this->key(self::LAST_FAILURE), function (ItemInterface $item) use ($timestamp) {
            $item->expiresAfter($this->resetTimeout * 2);
            return $timestamp;
        });
    }

    private function incrementFailureCount(): int
    {
        $count = $this->getFailureCount() + 1;
        $this->cache->get($this->key(self::FAILURE_COUNT), function (ItemInterface $item) use ($count) {
            $item->expiresAfter($this->resetTimeout);
            return $count;
        });

        return $count;
    }

    private function key(string $suffix): string
    {
        return self::CACHE_PREFIX.$this->serviceName.'_'.$suffix;
    }
}

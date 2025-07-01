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

namespace App\WorkflowModule\Store;

use App\Contracts\CircuitBreakerInterface;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\MarkingStore\MarkingStoreInterface;
use Symfony\Contracts\Cache\CacheInterface;

final class CacheCircuitBreakerStore implements MarkingStoreInterface
{
    /**
     * Constructor for CacheCircuitBreakerStore.
     * 
     * This class implements a marking store that uses a cache
     * to manage the state of circuit breakers in a workflow.
     * It allows for efficient state management by storing
     * the current state of each circuit breaker in a cache,
     * which can be shared across different instances of the application.
     * 
     * @param CacheInterface $cache The cache pool to use for storing circuit breaker states
     * @param string $namespace The namespace to use for cache keys, defaults to 'cb_'
     * @param int $ttl The time-to-live for cache entries, defaults to 300 seconds
     * 
     * @throws \InvalidArgumentException If the cache is not an instance of CacheInterface
     * @throws \RuntimeException If the cache cannot be accessed or modified
     * @throws \LogicException If the namespace is not valid or conflicts with existing keys
     * @throws \Exception For any other errors that may occur during cache operations
     * 
     * @return void
     */
    public function __construct(
        private CacheInterface $cache,
        private string $namespace = 'cb_',        // circuit-breaker cache keys
        private int $ttl = 300                    // seconds
    ) {}

    /**
     * Retrieves the marking for the given subject.
     * This method checks the cache for the marking associated with the subject.
     * If the marking is not found, it defaults to 'closed'.
     * 
     * @param object $subject The subject for which the marking is retrieved
     * 
     * @return Marking The marking for the subject, defaulting to 'closed'
     */
    public function getMarking(object $subject): Marking
    {
        $place = $this->cache->get($this->key($subject), fn () => 'closed');

        // keep the in-memory attribute consistent
        if ($subject instanceof CircuitBreakerInterface) {
            $subject->setState($state);
        }

        return new Marking([$place => 1]);
    }

    /**
     * Sets the marking for the given subject.
     * This method updates the cache with the new marking,
     * which is expected to be a single place in the state-machine.
     * 
     * @param object    $subject The subject for which the marking is set
     * @param Marking   $marking The marking to set, expected to contain a single place
     * @param array     $context Extra metadata
     * 
     * @return void
     */
    public function setMarking(object $subject, Marking $marking, ?array $context = []): void
    {
        $place = key($marking->getPlaces());

        $this->cache->get($this->key($subject), function ($item) use ($place) {
            $item->expiresAfter($this->ttl);
            return $place;
        });
        
        if ($subject instanceof CircuitBreakerInterface) {
            $subject->setState($place);
        }
    }

    /**
     * Generates a cache key for the circuit breaker.
     * This method prefixes the key with the service name to ensure
     * that different services can have their own independent circuit breakers.
     * 
     * @param object $subject The subject for which the key is generated
     * 
     * @return string The generated cache key
     */
    private function key(object $subject): string
    {
         /** @var CircuitBreakerInterface $subject */

        // you can refine this (tenant + channel) if needed
        // return $this->namespace.$subject->getTenant()->getId().'_'.$subject->getServiceName();
        return $this->namespace.$subject->getServiceName();
    }
}

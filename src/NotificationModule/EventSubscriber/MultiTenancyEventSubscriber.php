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

use App\MultitenancyModule\Entity\Tenant;
use App\MultitenancyModule\Contract\TenantContextInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Manages tenant context for notification processing.
 *
 * This subscriber ensures proper tenant context is set during requests and cleared afterward
 * to maintain strict tenant isolation in multi-tenant environments.
 */
final class MultiTenancyEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly TenantContextInterface $tenantContext
    ) {
    }

    /**
     * Defines the events this subscriber listens to.
     *
     * @return array<string, array<int, int|string>> The event names to listen to and their configuration
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest', 20] // Higher priority than security
            ],
            KernelEvents::RESPONSE => [
                ['onKernelResponse', -20] // Lower priority than most
            ],
        ];
    }

    /**
     * Sets tenant context at the start of each request.
     *
     * @param RequestEvent $event The kernel request event
     *
     * @throws \RuntimeException If tenant cannot be resolved from request
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $tenantId = $request->headers->get('X-Tenant-ID') 
            ?? $request->attributes->get('tenantId');

        if (null === $tenantId) {
            throw new \RuntimeException('Tenant context missing in request');
        }

        $tenant = $this->tenantContext->loadTenant($tenantId);
        $this->tenantContext->setCurrentTenant($tenant);
    }

    /**
     * Clears tenant context after response is sent.
     *
     * @param ResponseEvent $event The kernel response event
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $this->tenantContext->clearTenant();
    }
}
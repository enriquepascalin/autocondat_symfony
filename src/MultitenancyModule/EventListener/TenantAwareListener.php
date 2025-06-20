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

namespace App\MultitenancyModule\EventListener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use App\MultitenancyModule\Service\TenantContext;
use App\Contracts\TenantAwareInterface;

class TenantAwareListener
{
    /**
     * Constructor.
     *
     * @param TenantContext $tenantContext Tenant context service
     */
    public function __construct(
        private readonly TenantContext $tenantContext,
    ) {
    }

    /**
     * Handle pre-persist lifecycle event.
     *
     * @param PrePersistEventArgs $args Doctrine lifecycle event arguments
     */
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof TenantAwareInterface
            || null !== $entity->getTenant()) {
            return;
        }
        $tenant = $this->tenantContext->getCurrentTenant();
        if (null !== $tenant) {
            $entity->setTenant($tenant);
        }
    }
}

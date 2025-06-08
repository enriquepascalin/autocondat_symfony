<?php

declare(strict_types=1);

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Service\TenantContext;
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
     * @param LifecycleEventArgs $args Doctrine lifecycle event arguments
     */
    public function prePersist(LifecycleEventArgs $args): void
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

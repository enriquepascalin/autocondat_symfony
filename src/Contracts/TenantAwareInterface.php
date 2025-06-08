<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Entity\MultitenancyModule\Tenant;

/**
 * Interface TenantAwareInterface.
 *
 * This interface defines methods for entities that are aware of a tenant context.
 */
interface TenantAwareInterface
{
    /**
     * Get the tenant associated with the entity.
     */
    public function getTenant(): ?Tenant;

    /**
     * Set the tenant for the entity.
     */
    public function setTenant(?Tenant $tenant): self;
}

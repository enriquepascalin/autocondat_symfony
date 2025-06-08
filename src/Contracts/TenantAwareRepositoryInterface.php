<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Entity\MultitenancyModule\Tenant;
use Doctrine\ORM\QueryBuilder;

interface TenantAwareRepositoryInterface
{
    /**
     * Finds entities by criteria within a specific tenant scope.
     */
    public function findByTenant(array $criteria, Tenant $tenant): array;

    /**
     * Applies tenant filtering to a query builder.
     */
    public function applyTenantFilter(QueryBuilder $queryBuilder, Tenant $tenant, string $alias): void;
}

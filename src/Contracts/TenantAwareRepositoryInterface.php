<?php

namespace App\Contracts;

use App\Entity\MultitenancyModule\Tenant;
use Doctrine\ORM\QueryBuilder;

interface TenantAwareRepositoryInterface
{
    /**
     * Finds entities by criteria within a specific tenant scope.
     *
     * @param array $criteria
     * @param Tenant $tenant
     * @return array
     */
    public function findByTenant(array $criteria, Tenant $tenant): array;

    /**
     * Applies tenant filtering to a query builder.
     *
     * @param QueryBuilder $queryBuilder
     * @param Tenant $tenant
     * @param string $alias
     */
    public function applyTenantFilter(QueryBuilder $queryBuilder, Tenant $tenant, string $alias): void;
}
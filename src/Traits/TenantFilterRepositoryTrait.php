<?php

declare(strict_types=1);

namespace App\Traits;

use App\Entity\MultitenancyModule\Tenant;
use Doctrine\ORM\QueryBuilder;

trait TenantFilterRepositoryTrait
{
    /**
     * Applies tenant filtering to a query builder.
     */
    public function applyTenantFilter(
        QueryBuilder $queryBuilder,
        Tenant $tenant,
        string $alias = 'entity',
    ): void {
        $queryBuilder->andWhere("$alias.tenant = :tenant")
            ->setParameter('tenant', $tenant);
    }

    /**
     * Finds entities by criteria within a specific tenant scope.
     */
    public function findByTenant(
        array $criteria,
        Tenant $tenant,
    ): array {
        $queryBuilder = $this->createQueryBuilder('entity');
        $this->applyTenantFilter($queryBuilder, $tenant);

        foreach ($criteria as $field => $value) {
            $queryBuilder->andWhere("entity.$field = :$field")
                ->setParameter($field, $value);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}

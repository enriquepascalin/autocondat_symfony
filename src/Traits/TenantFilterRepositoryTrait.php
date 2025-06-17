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

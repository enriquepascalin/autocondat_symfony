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

namespace App\Repository\LocalizationModule;

use App\Entity\LocalizationModule\TranslationEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Contracts\TenantAwareRepositoryInterface;
use App\Traits\TenantFilterRepositoryTrait;

/**
 * @extends ServiceEntityRepository<TranslationEntry>
 */
class TranslationEntryRepository extends ServiceEntityRepository implements TenantAwareRepositoryInterface
{
    use TenantFilterRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TranslationEntry::class);
    }

    public function findByKeyAndLocale(string $key, string $locale, string $domain, ?string $tenantId): ?TranslationEntry
    {
        $criteria = [
            'key' => $key,
            'locale' => $locale,
            'domain' => $domain,
        ];

        if (null !== $tenantId) {
            $criteria['tenantId'] = $tenantId;
        }

        return $this->findOneBy($criteria);
    }
}

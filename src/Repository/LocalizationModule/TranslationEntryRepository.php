<?php

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

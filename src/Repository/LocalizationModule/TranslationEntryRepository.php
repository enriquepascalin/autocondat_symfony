<?php

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

        if ($tenantId !== null) {
            $criteria['tenantId'] = $tenantId;
        }

        return $this->findOneBy($criteria);
    }
}

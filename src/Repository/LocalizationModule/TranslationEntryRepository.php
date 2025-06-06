<?php

namespace App\Repository\LocalizationModule;

use App\Entity\LocalizationModule\TranslationEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TranslationEntry>
 */
class TranslationEntryRepository extends ServiceEntityRepository
{
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

//    /**
//     * @return TranslationEntry[] Returns an array of TranslationEntry objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TranslationEntry
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

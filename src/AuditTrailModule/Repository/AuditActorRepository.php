<?php
/**
 * Copyright © 2025 Enrique Pascalin <erparom@gmail.com>
 * This source code is protected under international copyright law.
 * All rights reserved. No warranty, explicit or implicit, provided.
 * Unauthorized copying of this file, via any medium, is strictly prohibited.
 * This file is confidential and only available to authorized individuals with
 * the permission of the copyright holders. If you encounter this file and do
 * not have permission, please contact the copyright holders and delete it.
 *
 * @author  Enrique Pascalin, Erparom Technologies
 * 
 * @version 1.0.0
 * 
 * @since   2025-06-18
 * 
 * @license license.md
 */
declare(strict_types=1);

namespace App\AuditTrailModule\Repository;

use App\AuditTrailModule\Entity\AuditActor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AuditActor>
 */
class AuditActorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuditActor::class);
    }

//    /**
//     * @return AuditActor[] Returns an array of AuditActor objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AuditActor
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

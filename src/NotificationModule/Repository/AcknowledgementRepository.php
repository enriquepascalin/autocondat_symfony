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

namespace App\NotificationModule\Repository;

use App\NotificationModule\Entity\Acknowledgement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * Repository for Acknowledgement entities.
 * 
 * Provides custom query methods for accessing acknowledgement data.
 * 
 * @method Acknowledgement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Acknowledgement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Acknowledgement[]    findAll()
 * @method Acknowledgement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AcknowledgementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Acknowledgement::class);
    }

    /**
     * Finds an acknowledgement by user and notification.
     *
     * @param User $user The user entity
     * @param Notification $notification The notification entity
     * @return Acknowledgement|null The acknowledgement or null if not found
     */
    public function findOneByUserAndNotification(User $user, Notification $notification): ?Acknowledgement
    {
        return $this->findOneBy([
            'user' => $user,
            'notification' => $notification
        ]);
    }

    /**
     * Finds expired acknowledgements (where expiresAt is in the past and action is SNOOZE).
     *
     * @return array<Acknowledgement> List of expired acknowledgements
     */
    public function findExpiredAcknowledgements(): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.expiresAt < :now')
            ->andWhere('a.action = :snooze')
            ->setParameter('now', new \DateTime())
            ->setParameter('snooze', AckActionEnum::SNOOZE)
            ->getQuery()
            ->getResult();
    }
}

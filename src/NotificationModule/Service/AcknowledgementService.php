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

namespace App\NotificationModule\Service;

use App\NotificationModule\Entity\{
    Acknowledgement,
    Notification,
    AckActionEnum,
    NotificationStatusEnum
};
use App\NotificationModule\Event\AcknowledgementReceivedEvent;
use App\UserModule\Entity\User;
use App\NotificationModule\Repository\AcknowledgementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Service for handling user acknowledgements of notifications.
 * 
 * This service manages all user interactions with notifications, including:
 * - Recording acknowledgement actions (read, dismiss, snooze, archive)
 * - Updating notification status based on user actions
 * - Processing expired acknowledgements (e.g., snoozed notifications)
 * - Maintaining audit trails of all acknowledgement activities
 */
final class AcknowledgementService
{
    /**
     * Constructor.
     *
     * @param EntityManagerInterface $entityManager Doctrine entity manager
     * @param EventDispatcherInterface $eventDispatcher Symfony event dispatcher
     * @param NotificationTrackerService $trackerService Notification tracker service
     * @param AcknowledgementRepository $ackRepository Acknowledgement repository
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly NotificationTrackerService $trackerService,
        private readonly AcknowledgementRepository $ackRepository
    ) {}

    /**
     * Records a user's acknowledgement action on a notification.
     *
     * @param User $user The user performing the action
     * @param Notification $notification The notification being acknowledged
     * @param AckActionEnum $action The acknowledgement action
     * @return Acknowledgement The created or updated acknowledgement
     * 
     * @throws \InvalidArgumentException If invalid action is provided
     * @throws \RuntimeException If database operation fails
     */
    public function recordAcknowledgement(
        User $user,
        Notification $notification,
        AckActionEnum $action
    ): Acknowledgement {
        // Find existing acknowledgement or create a new one
        $acknowledgement = $this->ackRepository->findOneByUserAndNotification($user, $notification)
            ?? new Acknowledgement();

        $acknowledgement
            ->setUser($user)
            ->setNotification($notification)
            ->setAction($action)
            ->setAcknowledgedAt(new \DateTimeImmutable());

        // Set expiration for snooze actions
        if ($action === AckActionEnum::SNOOZE) {
            $acknowledgement->setExpiresAt(
                new \DateTimeImmutable('+1 hour')
            );
        }

        $this->entityManager->persist($acknowledgement);
        $this->entityManager->flush();

        // Update notification status based on the action
        $this->updateNotificationStatus($notification, $action);

        // Dispatch event
        $this->eventDispatcher->dispatch(
            new AcknowledgementReceivedEvent($acknowledgement)
        );

        return $acknowledgement;
    }

    /**
     * Updates the status of a notification based on the acknowledgement action.
     *
     * @param Notification $notification The notification to update
     * @param AckActionEnum $action The acknowledgement action
     * @return void
     * 
     * @throws \LogicException If invalid status transition is attempted
     */
    private function updateNotificationStatus(
        Notification $notification,
        AckActionEnum $action
    ): void {
        $status = match ($action) {
            AckActionEnum::READ => NotificationStatusEnum::READ,
            AckActionEnum::DISMISS => NotificationStatusEnum::DISMISSED,
            AckActionEnum::ARCHIVE => NotificationStatusEnum::ARCHIVED,
            AckActionEnum::SNOOZE => NotificationStatusEnum::SNOOZED,
            default => $notification->getStatus()
        };

        $notification->setStatus($status);
        $this->entityManager->flush();

        // Update the tracker service
        $this->trackerService->updateStatus($notification, $status);
    }

    /**
     * Processes expired acknowledgements (e.g., snoozed notifications that have expired).
     *
     * @return int The number of processed acknowledgements
     * 
     * @throws \Doctrine\ORM\ORMException On Doctrine errors
     */
    public function processExpiredAcknowledgements(): int
    {
        $expiredAcks = $this->ackRepository->findExpiredAcknowledgements();
        $count = 0;

        foreach ($expiredAcks as $ack) {
            if ($ack->getAction() === AckActionEnum::SNOOZE) {
                $notification = $ack->getNotification();
                $notification->setStatus(NotificationStatusEnum::PENDING);
                $this->trackerService->reactivateNotification($notification);

                $this->entityManager->remove($ack);
                $count++;
            }
        }

        $this->entityManager->flush();
        return $count;
    }
}
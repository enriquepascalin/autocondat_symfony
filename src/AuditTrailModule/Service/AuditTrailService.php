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

namespace App\AuditTrailModule\Service;

use App\AuditTrailModule\Entity\AuditEvent;
use App\AuditTrailModule\Entity\AuditActorTypeEnum;
use App\AuditTrailModule\Entity\AuditSeverityEnum;
use App\AuditTrailModule\Repository\AuditEventRepository;
use App\MultitenancyModule\Service\TenantContext;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class AuditTrailService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AuditEventRepository $auditEventRepository,
        private readonly TenantContext $tenantContext,
        private readonly Security $security,
        private readonly LoggerInterface $auditLogger
    ) {
    }

    /**
     * Creates a new audit log entry with full context
     */
    public function logEvent(
        string $action,
        string $targetType,
        string $targetId,
        array $details = [],
        AuditSeverityEnum $severity = AuditSeverityEnum::INFO,
        AuditActorTypeEnum $actorType = AuditActorTypeEnum::SYSTEM
    ): AuditEvent {
        $auditEvent = new AuditEvent();
        $auditEvent->setTenantId($this->tenantContext->getCurrentTenantId());
        $auditEvent->setAction($action);
        $auditEvent->setTargetType($targetType);
        $auditEvent->setTargetId($targetId);
        $auditEvent->setDetails($details);
        $auditEvent->setSeverity($severity);
        $auditEvent->setActorType($actorType);

        // Add user context if available
        if ($this->security->getUser()) {
            $auditEvent->setActorId($this->security->getUser()->getId());
            $auditEvent->setActorType(AuditActorTypeEnum::USER);
        }

        try {
            $this->entityManager->persist($auditEvent);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            $this->auditLogger->error('Audit trail persistence failed', [
                'exception' => $e,
                'action' => $action,
                'target' => "{$targetType}:{$targetId}"
            ]);
        }

        return $auditEvent;
    }

    /**
     * Notification-specific audit helper
     */
    public function logNotificationStateChange(
        string $notificationId,
        string $oldStatus,
        string $newStatus,
        ?string $notificationType = null,
        ?string $channel = null
    ): AuditEvent {
        return $this->logEvent(
            action: 'NOTIFICATION_STATE_CHANGE',
            targetType: 'notification',
            targetId: $notificationId,
            details: [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'notification_type' => $notificationType,
                'channel' => $channel
            ],
            severity: AuditSeverityEnum::INFO
        );
    }

    /**
     * Delivery attempt logger
     */
    public function logDeliveryAttempt(
        string $notificationId,
        bool $success,
        ?string $error = null,
        ?string $providerResponse = null,
        int $recipientCount = 0
    ): AuditEvent {
        return $this->logEvent(
            action: $success ? 'DELIVERY_SUCCESS' : 'DELIVERY_FAILURE',
            targetType: 'notification',
            targetId: $notificationId,
            details: [
                'recipient_count' => $recipientCount,
                'error' => $error,
                'provider_response' => $providerResponse
            ],
            severity: $success ? AuditSeverityEnum::INFO : AuditSeverityEnum::WARNING
        );
    }

    /**
     * User acknowledgement logger
     */
    public function logUserAcknowledgement(
        string $notificationId,
        string $userId,
        string $actionType,
        ?string $notificationType = null
    ): AuditEvent {
        return $this->logEvent(
            action: 'USER_ACKNOWLEDGEMENT',
            targetType: 'notification',
            targetId: $notificationId,
            details: [
                'user_id' => $userId,
                'action' => $actionType,
                'notification_type' => $notificationType
            ],
            severity: AuditSeverityEnum::INFO,
            actorType: AuditActorTypeEnum::USER
        );
    }
}
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

use App\NotificationModule\Entity\Notification;
use App\NotificationModule\Entity\NotificationDelivery;
use App\NotificationModule\Entity\NotificationLog;
use App\NotificationModule\Entity\DeliveryStatusEnum;
use App\NotificationModule\Entity\NotificationStatusEnum;
use App\NotificationModule\Event\NotificationLifecycleEvent;
use App\NotificationModule\ValueObject\DeliveryReport;
use Doctrine\ORM\EntityManagerInterface;
//use OpenTelemetry\API\Trace\SpanKind;
//use OpenTelemetry\API\Trace\TracerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Component\Workflow\Event\Event as WorkflowEvent;
use App\AuditTrailModule\Service\AuditTrailService;
//use App\MetricsModule\Service\MetricsService;
use App\WorkflowModule\Contract\WorkflowSubjectInterface;

class NotificationTrackerService implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface $logger,
        //private readonly TracerInterface $tracer,
        private readonly AuditTrailService $auditTrail,
        //private readonly MetricsService $metrics,
        private readonly WorkflowInterface $notificationWorkflow
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            // Workflow events
            //'workflow.notification.transition' => 'onWorkflowTransition',
            //'workflow.notification.enter' => 'onWorkflowStateEntered',
            //'workflow.notification.announce' => 'onWorkflowAnnounce',
            
            // Custom lifecycle events
            NotificationLifecycleEvent::CREATED => 'onNotificationCreated',
            NotificationLifecycleEvent::SENT => 'onNotificationSent',
            NotificationLifecycleEvent::DELIVERED => 'onNotificationDelivered',
            NotificationLifecycleEvent::FAILED => 'onNotificationFailed',
            NotificationLifecycleEvent::ACKNOWLEDGED => 'onNotificationAcknowledged',
            NotificationLifecycleEvent::OPENED => 'onNotificationOpened',
        ];
    }

    /**
     * Handles workflow transitions
     * 
     * @param WorkflowEvent $event
     */
    public function onWorkflowTransition(WorkflowEvent $event): void
    {
        /** @var Notification $notification */
        $notification = $event->getSubject();
        $transition = $event->getTransition();
        
        $this->createNotificationLog(
            $notification,
            $notification->getStatus(),
            sprintf('Workflow transition: %s', $transition->getName())
        );
        
        $this->auditTrail->logWorkflowTransition(
            $notification->getTenantId(),
            $notification->getId(),
            $transition->getName()
        );
        
        $this->emitTransitionTelemetry($notification, $transition->getName());
    }

    /**
     * Handles entry into new states
     * 
     * @param WorkflowEvent $event
     */
    public function onWorkflowStateEntered(WorkflowEvent $event): void
    {
        /** @var Notification $notification */
        $notification = $event->getSubject();
        $state = implode(', ', array_keys($event->getMarking()->getPlaces()));
        
        $this->createNotificationLog(
            $notification,
            $notification->getStatus(),
            sprintf('Entered state: %s', $state)
        );
        
        /*$this->metrics->incrementCounter(
            'notification_state_entries',
            [
                'state' => $state,
                'tenant_id' => $notification->getTenantId()
            ]
        );*/
    }

    /**
     * Handles notification creation event
     * 
     * @param NotificationLifecycleEvent $event
     */
    public function onNotificationCreated(NotificationLifecycleEvent $event): void
    {
        $notification = $event->getNotification();
        
        $this->createNotificationLog(
            $notification,
            NotificationStatusEnum::CREATED,
            'Notification created'
        );
        
        $this->emitCreationTelemetry($notification);
    }

    /**
     * Handles notification sent event
     * 
     * @param NotificationLifecycleEvent $event
     */
    public function onNotificationSent(NotificationLifecycleEvent $event): void
    {
        $notification = $event->getNotification();
        $report = $event->getDeliveryReport();
        
        $delivery = $this->createDeliveryRecord(
            $notification,
            $report,
            DeliveryStatusEnum::SENT
        );
        
        $this->applyWorkflowTransition(
            $notification,
            'mark_sent',
            ['delivery_id' => $delivery->getId()]
        );
        
        $this->emitDeliveryTelemetry($notification, $report);
    }

    /**
     * Handles notification delivered event
     * 
     * @param NotificationLifecycleEvent $event
     */
    public function onNotificationDelivered(NotificationLifecycleEvent $event): void
    {
        $delivery = $event->getNotificationDelivery();
        $notification = $delivery->getNotification();
        
        $delivery->setStatus(DeliveryStatusEnum::DELIVERED);
        $delivery->setDeliveredAt(new \DateTimeImmutable());
        $this->em->flush();
        
        $this->createNotificationLog(
            $notification,
            NotificationStatusEnum::DELIVERED,
            sprintf('Delivered to %s', $delivery->getRecipientIdentifier())
        );
        
        $this->applyWorkflowTransition(
            $notification,
            'mark_delivered',
            ['delivery_id' => $delivery->getId()]
        );
        
        $this->emitDeliverySuccessTelemetry($delivery);
    }

    /**
     * Handles notification failure event
     * 
     * @param NotificationLifecycleEvent $event
     */
    public function onNotificationFailed(NotificationLifecycleEvent $event): void
    {
        $notification = $event->getNotification();
        $report = $event->getDeliveryReport();
        $error = $event->getError();
        
        $delivery = $this->createDeliveryRecord(
            $notification,
            $report,
            DeliveryStatusEnum::FAILED,
            $error
        );
        
        $this->applyWorkflowTransition(
            $notification,
            'mark_failed',
            [
                'delivery_id' => $delivery->getId(),
                'error' => $error
            ]
        );
        
        $this->emitDeliveryFailureTelemetry($notification, $report, $error);
    }

    /**
     * Applies a workflow transition with safety checks
     * 
     * @param Notification $notification
     * @param string $transitionName
     * @param array $context
     */
    private function applyWorkflowTransition(
        Notification $notification,
        string $transitionName,
        array $context = []
    ): void {
        if ($this->notificationWorkflow->can($notification, $transitionName)) {
            $this->notificationWorkflow->apply($notification, $transitionName, $context);
            $this->em->flush();
        } else {
            $this->logger->warning('Invalid workflow transition attempt', [
                'notification_id' => $notification->getId(),
                'transition' => $transitionName,
                'current_state' => $notification->getStatus()->value
            ]);
        }
    }

    /**
     * Creates a delivery record
     * 
     * @param Notification $notification
     * @param DeliveryReport $report
     * @param DeliveryStatusEnum $status
     * @param string|null $error
     * @return NotificationDelivery
     */
    private function createDeliveryRecord(
        Notification $notification,
        DeliveryReport $report,
        DeliveryStatusEnum $status,
        ?string $error = null
    ): NotificationDelivery {
        $delivery = new NotificationDelivery();
        $delivery
            ->setNotification($notification)
            ->setChannel($report->getChannel())
            ->setRecipient($report->getRecipient())
            ->setStatus($status)
            ->setSentAt(new \DateTimeImmutable())
            ->setDeliveryReport($report)
            ->setError($error)
            ->setTenantId($notification->getTenantId());
        
        $this->em->persist($delivery);
        $this->em->flush();
        
        return $delivery;
    }

    /**
     * Creates an immutable notification log
     * 
     * @param Notification $notification
     * @param NotificationStatusEnum $status
     * @param string $message
     * @return NotificationLog
     */
    private function createNotificationLog(
        Notification $notification,
        NotificationStatusEnum $status,
        string $message
    ): NotificationLog {
        $log = new NotificationLog();
        $log
            ->setNotificationId($notification->getId())
            ->setTenantId($notification->getTenantId())
            ->setStatus($status)
            ->setMessage($message)
            ->setLoggedAt(new \DateTimeImmutable())
            ->setMetadata([
                'recipients' => $notification->getRecipients(),
                'template' => $notification->getTemplate()->getId(),
                'content' => $notification->getContent()->getSubject()
            ]);
        
        $this->em->persist($log);
        $this->em->flush();
        
        // Send to centralized audit trail
        $this->auditTrail->logNotificationEvent(
            $notification->getTenantId(),
            $notification->getId(),
            $status,
            $message,
            $log->getMetadata()
        );
        
        return $log;
    }

    /**
     * Emits telemetry for notification creation
     * 
     * @param Notification $notification
     */
    private function emitCreationTelemetry(Notification $notification): void
    {
        /*$span = $this->tracer->spanBuilder('notification.created')
            ->setSpanKind(SpanKind::KIND_INTERNAL)
            ->setAttribute('tenant.id', $notification->getTenantId())
            ->setAttribute('notification.id', $notification->getId())
            ->setAttribute('notification.type', $notification->getType()->value)
            ->startSpan();
        
        $this->metrics->incrementCounter(
            'notifications_created_total',
            [
                'type' => $notification->getType()->value,
                'tenant_id' => $notification->getTenantId()
            ]
        );
        
        $span->end();*/
    }

    /**
     * Emits telemetry for workflow transitions
     * 
     * @param Notification $notification
     * @param string $transition
     */
    private function emitTransitionTelemetry(
        Notification $notification,
        string $transition
    ): void {
        /*$span = $this->tracer->spanBuilder('notification.transition')
            ->setSpanKind(SpanKind::KIND_INTERNAL)
            ->setAttribute('tenant.id', $notification->getTenantId())
            ->setAttribute('notification.id', $notification->getId())
            ->setAttribute('transition', $transition)
            ->startSpan();
        
        $this->metrics->incrementCounter(
            'notification_transitions_total',
            [
                'transition' => $transition,
                'tenant_id' => $notification->getTenantId()
            ]
        );
        
        $span->end();*/
    }

    /**
     * Emits telemetry for delivery attempts
     * 
     * @param Notification $notification
     * @param DeliveryReport $report
     */
    private function emitDeliveryTelemetry(
        Notification $notification,
        DeliveryReport $report
    ): void {
        /*$span = $this->tracer->spanBuilder('notification.delivery_attempt')
            ->setSpanKind(SpanKind::KIND_INTERNAL)
            ->setAttribute('notification.id', $notification->getId())
            ->setAttribute('channel', $report->getChannel()->value)
            ->startSpan();
        
        $this->metrics->incrementCounter(
            'notification_delivery_attempts_total',
            [
                'channel' => $report->getChannel()->value,
                'tenant_id' => $notification->getTenantId()
            ]
        );
        
        $span->end();*/
    }

    /**
     * Emits telemetry for successful deliveries
     * 
     * @param NotificationDelivery $delivery
     */
    private function emitDeliverySuccessTelemetry(
        NotificationDelivery $delivery
    ): void {
        /*$span = $this->tracer->spanBuilder('notification.delivered')
            ->setSpanKind(SpanKind::KIND_INTERNAL)
            ->setAttribute('delivery.id', $delivery->getId())
            ->setAttribute('latency_ms', $delivery->getLatencyMs())
            ->startSpan();
        
        $this->metrics->recordHistogram(
            'notification_delivery_latency_ms',
            $delivery->getLatencyMs(),
            [
                'channel' => $delivery->getChannel()->value,
                'tenant_id' => $delivery->getTenantId()
            ]
        );
        
        $span->end();*/
    }

    /**
     * Emits telemetry for failed deliveries
     * 
     * @param Notification $notification
     * @param DeliveryReport $report
     */
    public function trackStateChange(
        Notification $notification,
        NotificationStatusEnum $oldStatus
    ): void {
        $this->auditTrail->logNotificationStateChange(
            notificationId: $notification->getId(),
            oldStatus: $oldStatus->value,
            newStatus: $notification->getStatus()->value,
            notificationType: $notification->getType()->value,
            channel: $notification->getChannel()->getName()
        );
    }

    /**
     * Tracks delivery attempts and logs them
     * 
     * @param Notification $notification
     * @param bool $success
     * @param string|null $error
     */
    public function trackDeliveryAttempt(
        Notification $notification,
        bool $success,
        ?string $error = null
    ): void {
        $this->auditTrail->logDeliveryAttempt(
            notificationId: $notification->getId(),
            success: $success,
            error: $error,
            recipientCount: count($notification->getRecipients())
        );
    }

    /**
     * Transitions a notification through its workflow
     * 
     * @param WorkflowSubjectInterface $notification
     * @param string $transitionName
     */
    public function transitionNotification(
        WorkflowSubjectInterface $notification,
        string $transitionName
    ): void {
        if ($this->notificationWorkflow->can($notification, $transitionName)) {
            $this->notificationWorkflow->apply($notification, $transitionName);
        }
    }
}
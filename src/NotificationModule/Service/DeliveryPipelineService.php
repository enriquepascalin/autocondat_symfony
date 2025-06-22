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
use App\NotificationModule\Event\NotificationLifecycleEvent;
use App\NotificationModule\Exception\PipelineProcessingException;
use App\NotificationModule\Traits\NotificationSecurityTrait;
use App\NotificationModule\ValueObject\DeliveryReport;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class DeliveryPipelineService
{
    use NotificationSecurityTrait;

    public function __construct(
        private readonly TemplateRendererService $renderer,
        private readonly DeliveryRuleService $ruleService,
        private readonly ChannelRouterService $channelRouter,
        private readonly NotificationTrackerService $tracker,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Executes the full delivery pipeline for a notification
     *
     * @param Notification $notification Notification to process
     * 
     * @throws PipelineProcessingException When any pipeline stage fails
     * @throws \DomainException For tenant security violations
     */
    public function execute(Notification $notification): DeliveryReport
    {
        $this->validateTenantContext($notification->getTenantId());
        
        try {
            $this->dispatcher->dispatch(new NotificationLifecycleEvent(
                $notification,
                NotificationLifecycleEvent::STATE_PROCESSING
            ));

            $this->preProcess($notification);
            $this->applyRules($notification);
            $deliveryResult = $this->deliver($notification);
            $this->postProcess($notification, $deliveryResult);

            return $deliveryResult;
        } catch (\Throwable $e) {
            $this->logger->critical('Pipeline processing failed', [
                'notification' => $notification->getId(),
                'error' => $e->getMessage()
            ]);
            
            throw new PipelineProcessingException(
                $notification->getId(),
                'Pipeline execution aborted',
                $e
            );
        }
    }

    /**
     * Prepares notification for delivery
     */
    private function preProcess(Notification $notification): void
    {
        $this->tracker->markProcessing($notification);
        $this->renderer->validateTemplate($notification);
    }

    /**
     * Applies business rules to notification
     */
    private function applyRules(Notification $notification): void
    {
        $this->ruleService->applyRecipientRules($notification);
        $this->ruleService->applyScheduleRules($notification);
        $this->ruleService->applyRateLimits($notification);
    }

    /**
     * Executes channel delivery
     */
    private function deliver(Notification $notification): DeliveryReport
    {
        $report = new DeliveryReport($notification->getId());
        
        try {
            $this->channelRouter->route($notification);
            $report->markDelivered();
        } catch (\Throwable $e) {
            $report->markFailed($e->getMessage());
        }

        return $report;
    }

    /**
     * Handles post-delivery tasks
     */
    private function postProcess(Notification $notification, DeliveryReport $report): void
    {
        $this->tracker->recordDeliveryReport($notification, $report);
        
        $eventType = $report->isSuccessful()
            ? NotificationLifecycleEvent::STATE_DELIVERED
            : NotificationLifecycleEvent::STATE_FAILED;
            
        $this->dispatcher->dispatch(new NotificationLifecycleEvent(
            $notification,
            $eventType
        ));
    }
}
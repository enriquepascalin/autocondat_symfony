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
use App\NotificationModule\Entity\NotificationTemplate;
use App\NotificationModule\Entity\Audience;
use App\NotificationModule\Entity\ScheduleRule;
use App\NotificationModule\Entity\NotificationTypeEnum;
use App\NotificationModule\Entity\NotificationStatusEnum;
use App\NotificationModule\Event\NotificationRequestedEvent;
use App\NotificationModule\Exception\NotificationValidationException;
use App\NotificationModule\Service\AudienceService;
use App\NotificationModule\Service\DeliveryRuleService;
use App\NotificationModule\Service\ScheduleRuleService;
use App\Traits\TenantAwareTrait;
use App\NotificationModule\Traits\NotificationSecurityTrait;
use App\NotificationModule\ValueObject\NotificationContent;
use App\NotificationModule\ValueObject\Recipient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\RecurringMessage;

class NotificationOrchestratorService
{
    use TenantAwareTrait;
    use NotificationSecurityTrait;

    /**
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param MessageBusInterface $bus
     * @param ValidatorInterface $validator
     * @param DeliveryRuleService $deliveryRuleService
     * @param AudienceService $audienceService
     * @param ScheduleRuleService $scheduleRuleService
     * @param LoggerInterface $logger
     * @param Schedule $scheduler
     * @param int $syncDispatchThreshold
     * @param RetryPolicy $defaultRetryPolicy
     * @param CircuitBreakerFactory $circuitBreakerFactory
     */
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly MessageBusInterface $bus,
        private readonly ValidatorInterface $validator,
        private readonly DeliveryRuleService $deliveryRuleService,
        private readonly AudienceService $audienceService,
        private readonly ScheduleRuleService $scheduleRuleService,
        private readonly LoggerInterface $logger,
        private readonly Schedule $scheduler,
        private readonly RetryPolicy $defaultRetryPolicy,
        private readonly CircuitBreakerFactory $circuitBreakerFactory,
        private readonly int $syncDispatchThreshold = 100,
    ) {}

    /**
     * Creates and dispatches a notification
     *
     * @param NotificationTypeEnum $type
     * @param NotificationTemplate $template
     * @param NotificationContent $content
     * @param Audience|null $audience
     * @param Recipient[] $immediateRecipients
     * @param array $options
     * @return Notification
     *
     * @throws NotificationValidationException
     * @throws \RuntimeException
     */
    public function createAndDispatchNotification(
        NotificationTypeEnum $type,
        NotificationTemplate $template,
        NotificationContent $content,
        ?Audience $audience = null,
        array $immediateRecipients = [],
        array $options = []
    ): Notification {
        $this->validateTenantAccess($template);
        if ($audience) {
            $this->validateTenantAccess($audience);
        }

        $resolvedRecipients = $this->resolveRecipients($audience, $immediateRecipients);
        $notification = $this->createNotificationEntity(
            $type,
            $template,
            $content,
            $resolvedRecipients,
            $options
        );

        $this->validateDeliveryRules($notification);
        $this->persistNotification($notification);
        $this->dispatchBasedOnType($notification, $resolvedRecipients);

        return $notification;
    }

    /**
     * Resolves recipients from audience and immediate recipients
     *
     * @param Audience|null $audience
     * @param Recipient[] $immediateRecipients
     * @return Recipient[]
     */
    private function resolveRecipients(?Audience $audience, array $immediateRecipients): array
    {
        $resolved = [];

        if ($audience) {
            $resolved = $this->audienceService->resolve($audience);
        }

        foreach ($immediateRecipients as $recipient) {
            if ($recipient instanceof Recipient) {
                $resolved[] = $recipient;
            }
        }

        return $this->applySecurityFilters($resolved);
    }

    /**
     * Creates notification entity with audit trail
     *
     * @param NotificationTypeEnum $type
     * @param NotificationTemplate $template
     * @param NotificationContent $content
     * @param Recipient[] $recipients
     * @param array $options
     * @return Notification
     */
    private function createNotificationEntity(
        NotificationTypeEnum $type,
        NotificationTemplate $template,
        NotificationContent $content,
        array $recipients,
        array $options
    ): Notification {
        $notification = new Notification();
        $notification
            ->setType($type)
            ->setTemplate($template)
            ->setContent($content)
            ->setRecipients($recipients)
            ->setTenantId($this->getCurrentTenantId())
            ->setPriority($options['priority'] ?? PriorityLevelEnum::MEDIUM)
            ->setScheduleRule($options['schedule_rule'] ?? null)
            ->setRetryPolicy($options['retry_policy'] ?? $this->defaultRetryPolicy)
            ->setMaxRetries($options['max_retries'] ?? 3)
            ->setMetadata($options['metadata'] ?? []);

        $this->auditCreation($notification);

        return $notification;
    }

    /**
     * Validates delivery rules and entity constraints
     *
     * @param Notification $notification
     * @throws NotificationValidationException
     */
    private function validateDeliveryRules(Notification $notification): void
    {
        $violations = $this->validator->validate($notification);
        if (count($violations) > 0) {
            throw new NotificationValidationException($violations);
        }

        $this->deliveryRuleService->validateNotification($notification);
    }

    /**
     * Persists notification with transactional safety
     *
     * @param Notification $notification
     * @throws \RuntimeException
     */
    private function persistNotification(Notification $notification): void
    {
        try {
            $this->em->beginTransaction();
            $this->em->persist($notification);
            $this->em->flush();
            $this->em->commit();
        } catch (\Throwable $e) {
            $this->em->rollback();
            $this->logger->critical('Notification persistence failed', [
                'error' => $e->getMessage(),
                'notification' => $notification->getId() ?: 'unsaved'
            ]);
            throw new \RuntimeException('Notification persistence error');
        }
    }

    /**
     * Selects dispatch strategy based on notification type
     *
     * @param Notification $notification
     * @param Recipient[] $recipients
     */
    private function dispatchBasedOnType(Notification $notification, array $recipients): void
    {
        try {
            if ($notification->getType() === NotificationTypeEnum::SCHEDULED) {
                $this->dispatchScheduled($notification);
            } elseif (count($recipients) <= $this->syncDispatchThreshold) {
                $this->dispatchSynchronous($notification);
            } else {
                $this->dispatchAsynchronous($notification);
            }
        } catch (\Throwable $e) {
            $this->logger->error('Notification dispatch failed', [
                'error' => $e->getMessage(),
                'notification' => $notification->getId()
            ]);
            $this->handleDispatchFailure($notification);
        }
    }

    /**
     * Handles scheduled notifications using complex schedule rules
     *
     * @param Notification $notification
     */
    private function dispatchScheduled(Notification $notification): void
    {
        $scheduleRule = $notification->getScheduleRule();
        if (!$scheduleRule) {
            throw new \LogicException('Scheduled notification requires a schedule rule');
        }

        // Generate triggers based on complex business rules
        $triggers = $this->scheduleRuleService->generateTriggers($scheduleRule);

        foreach ($triggers as $trigger) {
            $this->scheduler->add(
                RecurringMessage::trigger($trigger, new SendNotificationMessage($notification->getId()))
            );
        }

        $notification->setStatus(NotificationStatusEnum::SCHEDULED);
        $this->em->flush();
    }

    /**
     * Dispatches notification synchronously with circuit breaker protection
     *
     * @param Notification $notification
     */
    private function dispatchSynchronous(Notification $notification): void
    {
        $circuitBreaker = $this->circuitBreakerFactory->create('sync_dispatch');
        
        $operation = function() use ($notification) {
            $event = new NotificationRequestedEvent($notification, true);
            $this->dispatcher->dispatch($event);
        };

        $this->executeWithCircuitBreaker($circuitBreaker, $operation);
    }

    /**
     * Dispatches notification asynchronously via message bus
     *
     * @param Notification $notification
     */
    private function dispatchAsynchronous(Notification $notification): void
    {
        $this->bus->dispatch(new SendNotificationMessage($notification->getId()));
    }

    /**
     * Executes operation with circuit breaker protection
     *
     * @param CircuitBreaker $circuitBreaker
     * @param callable $operation
     * @param int $maxRetries
     * @param int $retryDelay
     */
    private function executeWithCircuitBreaker(
        CircuitBreaker $circuitBreaker, 
        callable $operation,
        int $maxRetries = 3,
        int $retryDelay = 100
    ): void {
        $retryCount = 0;
        
        while ($retryCount <= $maxRetries) {
            if (!$circuitBreaker->isAvailable()) {
                throw new ServiceUnavailableException('Service unavailable due to circuit breaker');
            }

            try {
                $circuitBreaker->beforeCall();
                $operation();
                $circuitBreaker->recordSuccess();
                return;
            } catch (\Throwable $e) {
                $circuitBreaker->recordFailure();
                $retryCount++;
                
                if ($retryCount <= $maxRetries) {
                    usleep($retryDelay * 1000);
                    $retryDelay *= 2; // Exponential backoff
                } else {
                    throw $e;
                }
            }
        }
    }

    /**
     * Handles dispatch failures with circuit breaker pattern
     *
     * @param Notification $notification
     */
    private function handleDispatchFailure(Notification $notification): void
    {
        $notification->setStatus(NotificationStatusEnum::DISPATCH_FAILED);
        $this->em->flush();

        if ($notification->isCritical()) {
            $this->escalateCriticalFailure($notification);
        }
    }

    /**
     * Escalates critical notification failures
     *
     * @param Notification $notification
     */
    private function escalateCriticalFailure(Notification $notification): void
    {
        $this->bus->dispatch(new CriticalNotificationFailureMessage($notification->getId()));
        $this->logger->emergency('Critical notification failure', [
            'notification' => $notification->getId(),
            'priority' => $notification->getPriority()
        ]);
    }
}
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
use App\NotificationModule\Entity\Recipient;
use App\NotificationModule\Exception\RuleViolationException;
use App\NotificationModule\Repository\DeliveryRuleRepository;
use App\NotificationModule\Service\ScheduledRuleService;
use App\NotificationModule\Traits\NotificationSecurityTrait;
use App\NotificationModule\ValueObject\Recipient as RecipientVO;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;

final class DeliveryRuleService
{
    use NotificationSecurityTrait;

    private const RATE_LIMIT_POLICIES = [
        'tenant_global' => 'tenant_global_limiter',
        'channel_email' => 'channel_email_limiter',
        'type_marketing' => 'type_marketing_limiter',
    ];

    public function __construct(
        private readonly DeliveryRuleRepository $ruleRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly RateLimiterFactory $tenantRateLimiterFactory,
        private readonly LoggerInterface $logger,
        private readonly ScheduledRuleService $scheduler
    ) {
    }

    /**
     * Applies all business rules to a notification
     *
     * @param Notification $notification Notification to process
     * 
     * @throws RuleViolationException When any rule prevents delivery
     * @throws \DomainException For tenant security violations
     */
    public function applyRules(Notification $notification): void
    {
        $this->validateTenantContext($notification->getTenantId());
        
        $this->applyRecipientRules($notification);
        $this->applyScheduleRules($notification);
        $this->applyRateLimits($notification);
        $this->applyContentRules($notification);
    }

    /**
     * Applies recipient-specific rules
     *
     * @throws RuleViolationException When recipient rules are violated
     */
    public function applyRecipientRules(Notification $notification): void
    {
        $activeRecipients = [];
        
        foreach ($notification->getRecipients() as $recipient) {
            try {
                $this->validateRecipient($recipient, $notification);
                $activeRecipients[] = $recipient;
            } catch (RuleViolationException $e) {
                $this->logger->info('Recipient excluded', [
                    'recipient' => $recipient->getIdentifier(),
                    'rule' => $e->getRuleCode(),
                    'notification' => $notification->getId()
                ]);
            }
        }
        
        if (count($activeRecipients) === 0) {
            throw new RuleViolationException(
                'NOTIFICATION_NO_VALID_RECIPIENTS',
                'All recipients excluded by rules'
            );
        }
        
        $notification->setRecipients($activeRecipients);
    }

    /**
     * Applies scheduling rules
     *
     * @throws RuleViolationException When scheduling rules prevent delivery
     */
    public function applyScheduleRules(Notification $notification): void
    {
        if (!$this->scheduler->isWithinDeliveryWindow($notification)) {
            throw new RuleViolationException(
                'SCHEDULE_VIOLATION',
                'Outside allowed delivery window'
            );
        }

        if ($delay = $this->scheduler->getRequiredDelay($notification)) {
            $notification->setScheduledAt(
                (new \DateTimeImmutable())->add($delay)
            );
        }
    }

    /**
     * Applies rate limiting rules
     *
     * @throws RuleViolationException When rate limits are exceeded
     */
    public function applyRateLimits(Notification $notification): void
    {
        $limiterId = $this->getLimiterId($notification);
        $limiter = $this->tenantRateLimiterFactory->create($limiterId);
        
        if (!$limiter->consume()->isAccepted()) {
            throw new RuleViolationException(
                'RATE_LIMIT_EXCEEDED',
                sprintf('Rate limit exceeded for policy: %s', $limiterId)
            );
        }
    }

    /**
     * Applies content validation rules
     *
     * @throws RuleViolationException When content rules are violated
     */
    public function applyContentRules(Notification $notification): void
    {
        $rules = $this->ruleRepository->findActiveForTenant(
            $notification->getTenantId(),
            $notification->getType()
        );
        
        foreach ($rules as $rule) {
            if (!$rule->validate($notification)) {
                throw new RuleViolationException(
                    $rule->getViolationCode(),
                    $rule->getDescription()
                );
            }
        }
    }

    /**
     * Validates a single recipient against all rules
     *
     * @throws RuleViolationException When recipient is excluded
     */
    private function validateRecipient(RecipientVO $recipient, Notification $notification): void
    {
        $entity = $this->entityManager->getRepository(Recipient::class)
            ->findByIdentifier($recipient->getIdentifier());
        
        if (!$entity) {
            throw new RuleViolationException(
                'RECIPIENT_NOT_FOUND',
                'Recipient not in system'
            );
        }
        
        if ($entity->isOptedOut($notification->getType())) {
            throw new RuleViolationException(
                'RECIPIENT_OPTED_OUT',
                'Recipient opted out of this notification type'
            );
        }
        
        if ($entity->isBounced()) {
            throw new RuleViolationException(
                'RECIPIENT_BOUNCED',
                'Recipient has previous bounce history'
            );
        }
    }

    /**
     * Gets the appropriate rate limiter ID for the notification
     */
    private function getLimiterId(Notification $notification): string
    {
        $type = $notification->getType();
        $channel = $notification->getChannel()->getType();
        
        return match (true) {
            $type === 'MARKETING' => self::RATE_LIMIT_POLICIES['type_marketing'],
            $channel === 'EMAIL' => self::RATE_LIMIT_POLICIES['channel_email'],
            default => self::RATE_LIMIT_POLICIES['tenant_global']
        };
    }
}
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

use App\NotificationModule\Contract\ChannelSenderInterface;
use App\NotificationModule\Entity\Channel;
use App\NotificationModule\Entity\Notification;
use App\NotificationModule\Repository\ChannelRepository;
use App\NotificationModule\Traits\NotificationSecurityTrait;
use App\NotificationModule\ValueObject\Recipient;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final class ChannelRouterService
{
    use NotificationSecurityTrait;

    /** @var array<string, ChannelSenderInterface> */
    private array $senders = [];

    public function __construct(
        private readonly ChannelRepository $channelRepository,
        private readonly NotificationCircuitBreaker $circuitBreaker,
        private readonly LoggerInterface $logger,
        #[TaggedIterator('notification.channel_sender')] iterable $senders
    ) {
        foreach ($senders as $sender) {
            $this->registerSender($sender);
        }
    }

    /**
     * Routes notification through optimal channel
     *
     * @param Notification $notification Notification to deliver
     * 
     * @throws \RuntimeException When no available channels
     * @throws \DomainException For tenant security violations
     */
    public function route(Notification $notification): void
    {
        $this->validateTenantContext($notification->getTenantId());
        
        $channel = $this->selectOptimalChannel($notification);
        $sender = $this->getSenderForChannel($channel);
        
        $this->circuitBreaker->checkAvailability($channel);
        
        try {
            foreach ($notification->getRecipients() as $recipient) {
                $sender->send($recipient, $notification);
            }
            $this->circuitBreaker->recordSuccess($channel);
        } catch (\Throwable $e) {
            $this->circuitBreaker->recordFailure($channel);
            $this->logger->error('Channel delivery failure', [
                'channel' => $channel->getName(),
                'error' => $e->getMessage(),
                'notification' => $notification->getId()
            ]);
            throw $e;
        }
    }

    /**
     * Registers channel sender implementation
     */
    private function registerSender(ChannelSenderInterface $sender): void
    {
        $this->senders[$sender::class] = $sender;
    }

    /**
     * Selects optimal delivery channel based on business rules
     *
     * @param Notification $notification Notification context
     * 
     * @throws \RuntimeException When no suitable channel found
     */
    private function selectOptimalChannel(Notification $notification): Channel
    {
        $candidates = $this->channelRepository->findByTenantAndType(
            $notification->getTenantId(),
            $notification->getChannelType()
        );

        foreach ($candidates as $channel) {
            if ($this->isChannelOperational($channel)) {
                return $channel;
            }
        }

        throw new \RuntimeException('No operational channels available');
    }

    /**
     * Checks channel operational status
     */
    private function isChannelOperational(Channel $channel): bool
    {
        return $this->circuitBreaker->isAvailable($channel) 
            && $this->getSenderForChannel($channel)->getHealthStatus() > 0.5;
    }

    /**
     * Gets sender implementation for channel
     *
     * @throws \RuntimeException When sender not found
     */
    private function getSenderForChannel(Channel $channel): ChannelSenderInterface
    {
        foreach ($this->senders as $sender) {
            if ($sender->supports($channel->getType())) {
                return $sender;
            }
        }

        throw new \RuntimeException(sprintf(
            'No sender implementation for channel type "%s"',
            $channel->getType()
        ));
    }
}
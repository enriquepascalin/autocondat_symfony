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

namespace App\NotificationModule\Contract;

use App\NotificationModule\Entity\Notification;
use App\NotificationModule\ValueObject\Recipient;

interface ChannelSenderInterface
{
    /**
     * Sends notification content to a recipient through a specific channel
     *
     * @param Recipient $recipient Target notification recipient
     * @param Notification $notification Full notification context
     * @param array<string, mixed> $options Channel-specific sending options
     * 
     * @throws \RuntimeException When message delivery fails
     * @throws \InvalidArgumentException For invalid recipient/channel combinations
     */
    public function send(Recipient $recipient, Notification $notification, array $options = []): void;

    /**
     * Checks if channel supports the notification type
     *
     * @param string $notificationType Notification type enum value
     */
    public function supports(string $notificationType): bool;

    /**
     * Gets channel's maximum throughput capacity
     *
     * @return int Messages per second
     */
    public function getCapacity(): int;

    /**
     * Gets current channel health status
     *
     * @return float Value between 0.0 (unavailable) and 1.0 (fully operational)
     */
    public function getHealthStatus(): float;
}
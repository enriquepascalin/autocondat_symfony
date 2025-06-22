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

namespace App\NotificationModule\ValueObject;

final class DeliveryReport
{
    private bool $isSuccessful = false;
    private ?string $error = null;
    private \DateTimeImmutable $timestamp;

    public function __construct(
        private readonly string $notificationId
    ) {
        $this->timestamp = new \DateTimeImmutable();
    }

    public function markDelivered(): void
    {
        $this->isSuccessful = true;
        $this->error = null;
    }

    public function markFailed(string $error): void
    {
        $this->isSuccessful = false;
        $this->error = $error;
    }

    public function isSuccessful(): bool
    {
        return $this->isSuccessful;
    }

    public function getNotificationId(): string
    {
        return $this->notificationId;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->timestamp;
    }
}
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

use Webmozart\Assert\Assert;

/**
 * Defines retry behavior for notification delivery attempts
 */
final class RetryPolicy
{
    public function __construct(
        public readonly int $maxAttempts,
        public readonly int $initialDelay,
        public readonly float $multiplier,
        public readonly int $maxDelay
    ) {
        Assert::positiveInteger($maxAttempts, 'Max attempts must be positive integer');
        Assert::positiveInteger($initialDelay, 'Initial delay must be positive integer (ms)');
        Assert::positiveFloat($multiplier, 'Multiplier must be positive float');
        Assert::positiveInteger($maxDelay, 'Max delay must be positive integer (ms)');
        Assert::lessThanEq($initialDelay, $maxDelay, 'Initial delay cannot exceed max delay');
    }

    /**
     * Calculates next retry delay
     */
    public function calculateDelay(int $attempt): int
    {
        $delay = (int) ($this->initialDelay * pow($this->multiplier, $attempt - 1));
        return min($delay, $this->maxDelay);
    }
}
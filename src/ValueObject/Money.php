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

namespace App\ValueObject;

use Webmozart\Assert\Assert;

/**
 * Monetary amount in minor units to guarantee precision.
 */
final readonly class Money
{
    public function __construct(
        public int $amount,
        public Currency $currency
    ) {
        Assert::greaterThanEq($amount, 0);
    }

    public function plus(self $other): self
    {
        Assert::true($this->currency->equals($other->currency));
        return new self($this->amount + $other->amount, $this->currency);
    }

    public function minus(self $other): self
    {
        Assert::true($this->currency->equals($other->currency));
        return new self($this->amount - $other->amount, $this->currency);
    }

    public function multiply(float $factor): self
    {
        return new self((int) round($this->amount * $factor), $this->currency);
    }

    public function equals(self $other): bool
    {
        return $this->amount === $other->amount && $this->currency->equals($other->currency);
    }
}

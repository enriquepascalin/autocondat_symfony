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
 * Percentage from 0.00 % to 100.00 %.
 */
final readonly class Percentage
{
    public function __construct(public float $value)
    {
        Assert::range($value, 0.0, 100.0);
    }

    public function toFraction(): float
    {
        return $this->value / 100.0;
    }
}

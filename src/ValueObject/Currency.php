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
 * ISO 4217 currency representation.
 */
final readonly class Currency
{
    public function __construct(
        public string $code,
        public int $fractionDigits = 2
    ) {
        Assert::regex($code, '/^[A-Z]{3}$/');
        Assert::range($fractionDigits, 0, 4);
    }

    public function equals(self $other): bool
    {
        return $this->code === $other->code;
    }
}

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

final readonly class Address
{
    public function __construct(
        public string $line1,
        public string $line2,
        public string $city,
        public string $state,
        public string $postalCode,
        public string $countryIso  // ISO-3166-1 alpha-2
    ) {
        Assert::notEmpty($line1);
        Assert::notEmpty($city);
        Assert::regex($postalCode, '/^[A-Za-z0-9\- ]{3,10}$/');
        Assert::regex($countryIso, '/^[A-Z]{2}$/');
    }

    public function equals(self $other): bool
    {
        return $this->line1 === $other->line1
            && $this->city === $other->city
            && $this->postalCode === $other->postalCode
            && $this->countryIso === $other->countryIso;
    }
}

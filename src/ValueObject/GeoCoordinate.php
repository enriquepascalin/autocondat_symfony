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
 * WGS-84 latitude/longitude pair.
 */
final readonly class GeoCoordinate
{
    public function __construct(
        public float $latitude,
        public float $longitude
    ) {
        Assert::range($latitude, -90.0, 90.0);
        Assert::range($longitude, -180.0, 180.0);
    }
}

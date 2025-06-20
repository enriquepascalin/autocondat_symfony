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

namespace App\SubscriptionModule\Entity;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ServiceTypeEnum: int implements TranslatableInterface
{
    case SUPPORT = 0;        // Support
    case TRAINING = 1;       // Training
    case CONSULTING = 2;     // Consulting
    case DEVELOPMENT = 3;    // Development
    case MAINTENANCE = 4;    // Maintenance
    case INFRASTRUCTURE = 5; // Infrastructure
    case OTHER = 6;          // Other

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('service_type.'.$this->name, [], 'enums', $locale);
    }
}

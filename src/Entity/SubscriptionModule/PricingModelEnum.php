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

namespace App\Entity\SubscriptionModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum PricingModelEnum: int implements TranslatableInterface
{
    case FREE = 0;           // Free
    case ONE_TIME = 1;       // One-time
    case SUBSCRIPTION = 2;   // Subscription
    case USAGE_BASED = 3;    // Usage-based
    case CREDIT_BASED = 4;   // Credit-based
    case PAY_AS_YOU_GO = 5;  // Pay as you go
    case CUSTOM = 6;         // Custom

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('pricing_model.'.$this->name, [], 'enums', $locale);
    }
}

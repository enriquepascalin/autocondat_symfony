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

namespace App\Entity\NotificationModule;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum DeliveryStatusEnum: int implements TranslatableInterface
{
    case QUEUED = 0;    // Queued
    case SENT = 1;      // Sent
    case DELIVERED = 2; // Delivered
    case FAILED = 3;    // Failed

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('delivery_status.'.$this->name, [], 'enums', $locale);
    }
}

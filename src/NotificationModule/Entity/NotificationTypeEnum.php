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

namespace App\NotificationModule\Entity;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum NotificationTypeEnum: int implements TranslatableInterface
{
    case ALERT = 0;           // Urgent system message (e.g., downtime).
    case REMINDER = 1;        // Time-based reminder (e.g., "Meeting in 15m").
    case SYSTEM = 2;          // General system update.
    case ACTION_REQUIRED = 3; // User must take action (e.g., sign a doc).

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('notification_type.'.$this->name, [], 'enums', $locale);
    }
}

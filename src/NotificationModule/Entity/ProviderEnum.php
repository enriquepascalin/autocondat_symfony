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

enum ProviderEnum: int implements TranslatableInterface
{
    case SYMFONY_MAILER = 0; // Symfony Mailer
    case TWILIO = 1;         // Twilio
    case CUSTOM_API = 2;     // Custom API

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('provider.'.$this->name, [], 'enums', $locale);
    }
}

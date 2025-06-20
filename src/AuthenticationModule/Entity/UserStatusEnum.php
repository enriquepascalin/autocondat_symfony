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

namespace App\AuthenticationModule\Entity;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum UserStatusEnum: int implements TranslatableInterface
{
    case ACTIVE = 0;          // User is active and can log in.
    case INACTIVE = 1;        // User is inactive, cannot log in.
    case SUSPENDED = 2;       // User is suspended, cannot log in but may be reactivated.
    case DELETED = 3;         // User account is deleted, cannot log in and data may be purged..

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('user_status.'.$this->name, [], 'enums', $locale);
    }
}

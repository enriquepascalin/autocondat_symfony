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

namespace App\SupportModule\Entity;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum SeverityEnum: int implements TranslatableInterface
{
    case LOW = 0;        // Low
    case MEDIUM = 1;     // Medium
    case HIGH = 2;       // High
    case CRITICAL = 3;   // Critical
    case EMERGENCY = 4;  // Emergency
    case BLOCKER = 5;    // Blocker

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('severity.'.$this->name, [], 'enums', $locale);
    }
}

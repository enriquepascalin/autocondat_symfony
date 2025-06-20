<?php
/**
 * Copyright © 2025 Enrique Pascalin <erparom@gmail.com>
 * This source code is protected under international copyright law.
 * All rights reserved. No warranty, explicit or implicit, provided.
 * Unauthorized copying of this file, via any medium, is strictly prohibited.
 * This file is confidential and only available to authorized individuals with
 * the permission of the copyright holders. If you encounter this file and do
 * not have permission, please contact the copyright holders and delete it.
 *
 * @author  Enrique Pascalin, Erparom Technologies
 * 
 * @version 1.0.0
 * 
 * @since   2025-06-18
 * 
 * @license license.md
 */
declare(strict_types=1);

namespace App\AuditTrailModule\Entity;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Classifies the severity (priority) of an audit event.
 */
enum AuditSeverityEnum: string implements TranslatableInterface
{
    /** Normal operational event; no immediate action required. */
    case INFO = 'INFO';

    /** Anomalous condition that should be reviewed by operators. */
    case WARN = 'WARN';

    /** Critical event demanding immediate remediation or escalation. */
    case CRITICAL = 'CRITICAL';

    /**
     * Returns the translated label for this enum case.
     *
     * @param TranslatorInterface $translator Translator instance.
     * @param string|null         $locale     Optional locale override.
     *
     * @return string The translated label.
     */
    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('audit_severity.' . $this->name, [], 'enums', $locale);
    }
}

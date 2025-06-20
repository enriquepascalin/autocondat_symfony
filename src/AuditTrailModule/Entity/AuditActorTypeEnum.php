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

namespace App\AuditTrailModule\Entity;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Enumerates every actor category that can initiate an audit event.
 *
 * Each case maps to the i18n key pattern `audit_actor_type.<CASE_NAME>`
 * located in the translation catalogue `enums.*.yaml`.
 */
enum AuditActorTypeEnum: string implements TranslatableInterface
{
    /** Human end-user authenticated through the regular login flow. */
    case USER    = 'USER';

    /** Internal or external service acting via API tokens or webhooks. */
    case SERVICE = 'SERVICE';

    /** Automated system process or scheduled job without human intervention. */
    case SYSTEM  = 'SYSTEM';

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
        return $translator->trans('audit_actor_type.' . $this->name, [], 'enums', $locale);
    }
}

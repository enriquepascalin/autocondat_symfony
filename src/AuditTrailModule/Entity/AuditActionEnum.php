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
 * Enumerates every action category that an audit event can represent.
 *
 * The translation key pattern is: audit_action.<CASE_NAME>
 * and is stored in the catalogue `enums.*.yaml`.
 */
enum AuditActionEnum: string implements TranslatableInterface
{
    /** Creation of a new object or resource (CRUD “Create”). */
    case CREATE  = 'CREATE';

    /** Retrieval or viewing of a resource (CRUD “Read”). */
    case READ    = 'READ';

    /** Modification of an existing resource (CRUD “Update”). */
    case UPDATE  = 'UPDATE';

    /** Deletion or destruction of a resource (CRUD “Delete”). */
    case DELETE  = 'DELETE';

    /** Authentication attempt (login, logout, MFA, token refresh). */
    case AUTH    = 'AUTH';

    /** Generic access/use of a privileged operation or data export. */
    case ACCESS  = 'ACCESS';

    /** Background or automatic system activity (scheduler, worker). */
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
        return $translator->trans('audit_action.' . $this->name, [], 'enums', $locale);
    }
}

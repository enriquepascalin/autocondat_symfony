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

namespace App\Service\LocalizationModule;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Resolves translations via layered fallback:
 * 1. Tenant override
 * 2. Global override
 * 3. YAML fallback
 * 4. Google Translate (optional)
 */
class TranslationResolverService
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly TranslationManager $translationManager,
        private readonly GoogleTranslateService $googleTranslate,
    ) {
    }

    /**
     * Resolve translation string using fallback strategy.
     *
     * @param string       $key        Translation key
     * @param array        $parameters Parameters for replacement
     * @param ?string|null $domain     Translation domain (defaults to 'messages')
     * @param ?string|null $locale     Target locale (null = default locale)
     *
     * @return string Translated string
     */
    public function trans(string $key, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        $translated = $this->translator->trans($key, $parameters, $domain, $locale);

        if ($translated === $key) {
            $suggested = $this->googleTranslate->translate($key, $locale ?? 'en');
            $this->translationManager->createFallbackEntry($key, $suggested, $domain ?? 'messages', $locale);

            return $suggested;
        }

        return $translated;
    }
}

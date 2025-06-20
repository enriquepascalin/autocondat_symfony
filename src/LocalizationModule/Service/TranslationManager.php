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

namespace App\LocalizationModule\Service;

use App\Contracts\AutoTranslatorInterface;
use App\LocalizationModule\Entity\TranslationEntry;
use App\LocalizationModule\Entity\TranslationSourceEnum;
use App\LocalizationModule\Repository\TranslationEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Handles translation logic, caching, DB lookup and automatic fallback.
 */
class TranslationManager
{
    public function __construct(
        private TranslationEntryRepository $repository,
        private EntityManagerInterface $em,
        private CacheInterface $cache,
        private AutoTranslatorInterface $autoTranslator, // 🔧 This is the corrected argument name
    ) {
    }

    /**
     * Returns a translated string by checking cache, DB or auto-translating.
     */
    public function getTranslation(
        string $key,
        string $domain,
        string $locale,
        ?string $fallbackText = null,
        ?string $tenantId = null,
    ): string {
        $cacheKey = "trans_{$tenantId}_{$domain}_{$locale}_{$key}";

        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($key, $domain, $locale, $fallbackText, $tenantId) {
            $entry = $this->repository->findByKeyAndLocale($key, $locale, $domain, $tenantId);

            if ($entry) {
                return $entry->getValue();
            }

            if (!$fallbackText) {
                return $key;
            }

            $translated = $this->autoTranslator->translate($fallbackText, 'en', $locale);
            $this->createFallbackEntry($key, $translated, $domain, $locale, $tenantId);

            return $translated;
        });
    }

    /**
     * Creates a fallback translation entry in the database.
     *
     * This is used when no translation is found and we want to store a fallback
     * for future use, especially for auto-translated entries.
     *
     * @param string      $key      The translation key
     * @param string      $domain   The translation domain
     * @param string      $locale   The locale for the translation
     * @param string|null $tenantId Optional tenant ID for multi-tenant systems
     *
     * @return TranslationEntry The created translation entry
     */
    public function createFallbackEntry(
        string $key,
        string $translated,
        string $domain = 'messages',
        ?string $locale = null,
        ?string $tenantId = null,
    ): void {
        $entry = new TranslationEntry();
        $entry->setKey($key);
        $entry->setLocale($locale ?? 'en');
        $entry->setValue($translated);
        $entry->setDomain($domain);
        $entry->setSource(TranslationSourceEnum::AUTO);
        $entry->setTenantId($tenantId);

        $this->em->persist($entry);
        $this->em->flush();
    }
}

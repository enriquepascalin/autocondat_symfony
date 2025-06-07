<?php

namespace App\Service\LocalizationModule;

use App\Contracts\AutoTranslatorInterface;
use App\Entity\LocalizationModule\TranslationEntry;
use App\Entity\LocalizationModule\TranslationSourceEnum;
use App\Repository\LocalizationModule\TranslationEntryRepository;
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
        private AutoTranslatorInterface $autoTranslator // 🔧 This is the corrected argument name
    ) {}

    /**
     * Returns a translated string by checking cache, DB or auto-translating.
     */
    public function getTranslation(
        string $key,
        string $domain,
        string $locale,
        ?string $fallbackText = null,
        ?string $tenantId = null
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
     * @param string $key The translation key
     * @param string $domain The translation domain
     * @param string $locale The locale for the translation
     * @param string $value The translated value to store
     * @param string|null $tenantId Optional tenant ID for multi-tenant systems
     * @return TranslationEntry The created translation entry
     */
    public function createFallbackEntry(
        string $key,
        string $translated,
        string $domain = 'messages',
        ?string $locale = null,
        ?string $tenantId = null
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
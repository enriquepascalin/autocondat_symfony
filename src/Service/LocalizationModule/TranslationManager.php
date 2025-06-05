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

            $entry = new TranslationEntry();
            $entry->setKey($key);
            $entry->setLocale($locale);
            $entry->setValue($translated);
            $entry->setDomain($domain);
            $entry->setSource(TranslationSourceEnum::AUTO);
            $entry->setTenantId($tenantId);

            $this->em->persist($entry);
            $this->em->flush();

            return $translated;
        });
    }
}
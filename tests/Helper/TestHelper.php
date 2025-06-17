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

namespace App\Tests\Helper;

use App\Entity\LocalizationModule\TranslationEntry;
use App\Entity\LocalizationModule\TranslationSourceEnum;
use App\Repository\LocalizationModule\TranslationEntryRepository;
use App\Service\LocalizationModule\GoogleTranslateService;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

trait TestHelper
{
    /**
     * Returns a mock TranslationEntryRepository that will return the first matching entry for findByKeyAndLocale.
     * If an entry with matching key, locale, domain (and tenantId if provided) exists in $entries, it returns that entry; otherwise null.
     */
    protected function getMockTranslationEntryRepository(array $entries = []): TranslationEntryRepository
    {
        $repo = $this->createMock(TranslationEntryRepository::class);
        $repo->method('findByKeyAndLocale')
             ->willReturnCallback(function ($key, $locale, $domain, $tenantId = null) use ($entries) {
                 foreach ($entries as $entry) {
                     $matchesTenant = null === $tenantId
                         ? (null === $entry->getTenantId())
                         : ($entry->getTenantId() === $tenantId);
                     if ($entry->getKey() === $key && $entry->getLocale() === $locale && $entry->getDomain() === $domain && $matchesTenant) {
                         return $entry;
                     }
                 }

                 return null;
             });

        return $repo;
    }

    /**
     * Returns a mock CacheInterface that will always execute the provided callback on CacheInterface::get.
     * It simulates a cache miss every time (not storing values between calls).
     */
    protected function getMockCache(): CacheInterface
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')
              ->willReturnCallback(fn ($key, $callback) => $callback($this->createStub(ItemInterface::class)));

        return $cache;
    }

    /**
     * Returns a mock GoogleTranslateService (AutoTranslator) that returns a fixed translated text for any input.
     */
    protected function getMockGoogleService(string $translatedText = 'translated-text'): GoogleTranslateService
    {
        $service = $this->createMock(GoogleTranslateService::class);
        $service->method('translate')->willReturn($translatedText);

        return $service;
    }

    /**
     * Creates a sample TranslationEntry entity with preset fields for testing.
     */
    protected function getSampleEntry(): TranslationEntry
    {
        $entry = new TranslationEntry();
        $entry->setKey('hello');
        $entry->setValue('hola');
        $entry->setLocale('es');
        $entry->setDomain('messages');
        $entry->setSource(TranslationSourceEnum::MANUAL);

        // If tenantId property exists via TenantAwareTrait, leave as null for sample
        return $entry;
    }
}

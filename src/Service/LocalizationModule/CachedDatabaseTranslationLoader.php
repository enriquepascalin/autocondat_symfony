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

use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * Decorates database loader with Redis caching.
 */
class CachedDatabaseTranslationLoader implements LoaderInterface
{
    private const CACHE_KEY_TEMPLATE = 'trans_%s_%s';
    private const CACHE_TTL = 3600;

    /**
     * @param LoaderInterface $innerLoader Decorated database loader
     * @param CacheInterface  $cache       Cache pool instance
     */
    public function __construct(
        #private readonly LoaderInterface $innerLoader,
        #private readonly CacheInterface $cache,
    ) {
    }

    /**
     * Loads translations with Redis caching.
     */
    public function load($resource, string $locale, string $domain = 'messages'): MessageCatalogue
    {
        $cacheKey = sprintf(self::CACHE_KEY_TEMPLATE, $domain, $locale);

        return $this->cache->get($cacheKey, function () use ($resource, $locale, $domain) {
            return $this->innerLoader->load($resource, $locale, $domain);
        }, self::CACHE_TTL);
    }
}

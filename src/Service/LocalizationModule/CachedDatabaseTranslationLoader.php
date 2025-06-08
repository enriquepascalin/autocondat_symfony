<?php

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
        private readonly LoaderInterface $innerLoader,
        private readonly CacheInterface $cache,
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

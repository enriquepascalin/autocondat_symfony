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

namespace App\Tests\Service\LocalizationModule;

use App\Service\LocalizationModule\CachedDatabaseTranslationLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CachedDatabaseTranslationLoaderTest extends TestCase
{
    public function testLoadUsesCacheOnSubsequentCalls(): void
    {
        $locale = 'fr';
        $domain = 'messages';

        // Dummy inner loader to be called at most once
        $innerLoader = $this->createMock(LoaderInterface::class);
        $catalogue = new MessageCatalogue($locale);
        $catalogue->set('hello', 'bonjour', $domain);
        $innerLoader->expects($this->once())
                    ->method('load')
                    ->with(null, $locale, $domain)
                    ->willReturn($catalogue);

        // Cache mock that stores results by key
        $cacheStorage = [];
        $cacheMock = $this->createMock(CacheInterface::class);
        $cacheMock->method('get')
                  ->willReturnCallback(function ($key, $callback) use (&$cacheStorage) {
                      if (!array_key_exists($key, $cacheStorage)) {
                          $cacheStorage[$key] = $callback($this->createStub(ItemInterface::class));
                      }

                      return $cacheStorage[$key];
                  });

        $cachedLoader = new CachedDatabaseTranslationLoader($innerLoader, $cacheMock);

        // Act - first call triggers inner loader, second call should use cache
        $catalogue1 = $cachedLoader->load(null, $locale, $domain);
        $catalogue2 = $cachedLoader->load(null, $locale, $domain);

        // Assert same instance is returned from cache and contains expected data
        $this->assertSame($catalogue1, $catalogue2, 'The same MessageCatalogue instance should be returned on subsequent calls (cache hit).');
        $this->assertSame('bonjour', $catalogue2->get('hello', $domain));
    }

    public function testLoadCallsInnerLoaderForDifferentLocaleOrDomain(): void
    {
        $locale1 = 'en';
        $locale2 = 'es';
        $domain1 = 'messages';
        $domain2 = 'validators';

        // Two distinct catalogues for different locale/domain combos
        $catEnMessages = new MessageCatalogue($locale1);
        $catEnMessages->set('greet', 'hello', $domain1);
        $catEsMessages = new MessageCatalogue($locale2);
        $catEsMessages->set('greet', 'hola', $domain1);

        // Inner loader should be called for each unique combination
        $innerLoader = $this->createMock(LoaderInterface::class);
        $innerLoader->expects($this->exactly(2))
                    ->method('load')
                    ->withConsecutive(
                        [null, $locale1, $domain1],
                        [null, $locale2, $domain1]
                    )
                    ->willReturnOnConsecutiveCalls($catEnMessages, $catEsMessages);

        $cacheStorage = [];
        $cacheMock = $this->createMock(CacheInterface::class);
        $cacheMock->method('get')
                  ->willReturnCallback(function ($key, $callback) use (&$cacheStorage) {
                      if (!array_key_exists($key, $cacheStorage)) {
                          $cacheStorage[$key] = $callback($this->createStub(ItemInterface::class));
                      }

                      return $cacheStorage[$key];
                  });

        $cachedLoader = new CachedDatabaseTranslationLoader($innerLoader, $cacheMock);

        // Act
        $result1 = $cachedLoader->load(null, $locale1, $domain1);
        $result2 = $cachedLoader->load(null, $locale2, $domain1);

        // Assert: each call returns its respective catalogue
        $this->assertSame('hello', $result1->get('greet', $domain1));
        $this->assertSame('hola', $result2->get('greet', $domain1));
        $this->assertNotSame($result1, $result2, 'Different locale/domain combinations should produce separate cached entries.');
    }
}

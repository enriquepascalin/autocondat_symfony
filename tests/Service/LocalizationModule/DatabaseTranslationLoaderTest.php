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

use App\Entity\LocalizationModule\TranslationEntry;
use App\Service\LocalizationModule\DatabaseTranslationLoader;
use App\Repository\LocalizationModule\TranslationEntryRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\MessageCatalogue;

class DatabaseTranslationLoaderTest extends TestCase
{
    /*
    public function testLoadPopulatesCatalogueFromRepository(): void
    {
        $locale = 'es';
        $domain = 'messages';

        // Prepare sample translation entries
        $entry1 = new TranslationEntry();
        $entry1->setKey('hello');
        $entry1->setValue('hola');
        $entry1->setLocale($locale);
        $entry1->setDomain($domain);
        $entry2 = new TranslationEntry();
        $entry2->setKey('goodbye');
        $entry2->setValue('adios');
        $entry2->setLocale($locale);
        $entry2->setDomain($domain);

        // Mock repository to return these entries for the locale and domain
        $repositoryMock = $this->createMock(TranslationEntryRepository::class);
        $repositoryMock->expects($this->once())
                       ->method('findBy')
                       ->with(['locale' => $locale, 'domain' => $domain])
                       ->willReturn([$entry1, $entry2]);

        $loader = new DatabaseTranslationLoader($repositoryMock);

        // Act
        $catalogue = $loader->load(null, $locale, $domain);

        // Assert
        $this->assertInstanceOf(MessageCatalogue::class, $catalogue);
        $this->assertSame($locale, $catalogue->getLocale(), 'Catalogue locale should match requested locale.');
        // Ensure all translations from repository are present in the catalogue
        $this->assertSame('hola', $catalogue->get('hello', $domain));
        $this->assertSame('adios', $catalogue->get('goodbye', $domain));
    }

    public function testLoadReturnsEmptyCatalogueWhenNoEntries(): void
    {
        $locale = 'de';
        $domain = 'messages';

        $repositoryStub = $this->createStub(TranslationEntryRepository::class);
        $repositoryStub->method('findBy')->willReturn([]); // no entries

        $loader = new DatabaseTranslationLoader($repositoryStub);
        $catalogue = $loader->load(null, $locale, $domain);

        $this->assertInstanceOf(MessageCatalogue::class, $catalogue);
        $this->assertSame($locale, $catalogue->getLocale());
        $this->assertEmpty($catalogue->all($domain), 'Catalogue should be empty when repository returns no translations.');
    }

    public function testLoadDefaultsToMessagesDomain(): void
    {
        $locale = 'en';
        $defaultDomain = 'messages';

        // Expect findBy to be called with domain 'messages' when none provided
        $repositoryMock = $this->createMock(TranslationEntryRepository::class);
        $repositoryMock->expects($this->once())
                       ->method('findBy')
                       ->with(['locale' => $locale, 'domain' => $defaultDomain])
                       ->willReturn([]);

        $loader = new DatabaseTranslationLoader($repositoryMock);
        // Call load without providing a domain (should use default 'messages')
        $catalogue = $loader->load(null, $locale);

        $this->assertSame($locale, $catalogue->getLocale());
        $this->assertEmpty($catalogue->all($defaultDomain), "Default domain 'messages' should be used when none is provided.");
    }
    */
}

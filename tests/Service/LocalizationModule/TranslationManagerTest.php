<?php

namespace App\Tests\Service\LocalizationModule;

use App\Entity\LocalizationModule\TranslationEntry;
use App\Service\LocalizationModule\TranslationManager;
use App\Tests\Helper\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class TranslationManagerTest extends TestCase
{
    use TestHelper;

    public function testReturnsExistingTranslationFromDatabase(): void
    {
        // Arrange: repository has an entry for the given key/locale/domain
        $entry = $this->getSampleEntry();
        $expectedValue = $entry->getValue();
        $repository = $this->getMockTranslationEntryRepository([$entry]);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $cache = $this->getMockCache();
        $translator = $this->getMockGoogleService(); // Auto-translator (not expected to be used)

        // Expect no new translation creation since entry exists
        $entityManager->expects($this->never())->method('persist');
        $entityManager->expects($this->never())->method('flush');
        $translator->expects($this->never())->method('translate');

        // Act
        $manager = new TranslationManager($repository, $entityManager, $cache, $translator);
        $result = $manager->getTranslation($entry->getKey(), $entry->getDomain(), $entry->getLocale(), fallbackText: null);

        // Assert
        $this->assertSame($expectedValue, $result, "Should return the value from existing TranslationEntry.");
    }

    public function testReturnsKeyIfTranslationNotFoundAndNoFallbackProvided(): void
    {
        // Arrange: repository returns null (no entry found), and no fallback text is given
        $repository = $this->getMockTranslationEntryRepository([]);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $cache = $this->getMockCache();
        $translator = $this->getMockGoogleService();

        // Expect no new translation or auto-translation when fallback text is absent
        $entityManager->expects($this->never())->method('persist');
        $entityManager->expects($this->never())->method('flush');
        $translator->expects($this->never())->method('translate');

        $manager = new TranslationManager($repository, $entityManager, $cache, $translator);
        $missingKey = 'nonexistent.key';
        $locale = 'fr';
        $domain = 'messages';

        // Act
        $result = $manager->getTranslation($missingKey, $domain, $locale, fallbackText: null);

        // Assert
        $this->assertSame($missingKey, $result, "Should return the key itself when no translation and no fallback text are available.");
    }

    public function testAutoTranslatesAndPersistsNewEntryWhenNotFound(): void
    {
        // Arrange: repository has no entry, fallback text is provided for auto-translation
        $repository = $this->getMockTranslationEntryRepository([]);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $cache = $this->getMockCache();
        $translator = $this->createMock(\App\Contracts\AutoTranslatorInterface::class);
        $fallbackText = 'Hello World';
        $locale = 'fr';
        $domain = 'messages';
        $translatedText = 'Bonjour le monde';

        // Expect translator to be called once to translate fallback text from English to target locale
        $translator->expects($this->once())
                  ->method('translate')
                  ->with($fallbackText, 'en', $locale)
                  ->willReturn($translatedText);

        // Expect a new TranslationEntry to be persisted and flushed
        $entityManager->expects($this->once())
                      ->method('persist')
                      ->with($this->callback(function($entity) use ($fallbackText, $translatedText, $locale, $domain) {
                          $this->assertInstanceOf(TranslationEntry::class, $entity);
                          /** @var TranslationEntry $entity */
                          $this->assertSame($fallbackText, $entity->getKey(), "Persisted entry key should match fallback text key.");
                          $this->assertSame($locale, $entity->getLocale(), "Persisted entry locale should match target locale.");
                          $this->assertSame($domain, $entity->getDomain(), "Persisted entry domain should match.");
                          $this->assertSame($translatedText, $entity->getValue(), "Persisted entry value should match translated text.");
                          $source = $entity->getSource();
                          $this->assertTrue($source === \App\Entity\LocalizationModule\TranslationSourceEnum::AUTO || (string)$source === 'AUTO', "Source should be marked as AUTO.");
                          return true;
                      }));
        $entityManager->expects($this->once())->method('flush');

        $manager = new TranslationManager($repository, $entityManager, $cache, $translator);

        // Act
        $result = $manager->getTranslation('hello_world', $domain, $locale, $fallbackText);

        // Assert
        $this->assertSame($translatedText, $result, "Should return the translated text for a missing translation.");
    }

    public function testUsesTenantSpecificTranslationIfAvailable(): void
    {
        // Arrange: repository has a tenant-specific entry and also a global entry for the same key
        $tenantId = 'TENANT1';
        $globalEntry = $this->getSampleEntry(); // global (tenantId null)
        $tenantEntry = $this->getSampleEntry();
        $tenantEntry->setTenantId($tenantId);
        $tenantEntry->setValue('hola-tenant'); // different value to distinguish

        $entries = [$globalEntry, $tenantEntry];
        $repository = $this->getMockTranslationEntryRepository($entries);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $cache = $this->getMockCache();
        $translator = $this->getMockGoogleService();

        // Expect no fallback actions since tenant entry exists
        $entityManager->expects($this->never())->method('persist');
        $entityManager->expects($this->never())->method('flush');
        $translator->expects($this->never())->method('translate');

        $manager = new TranslationManager($repository, $entityManager, $cache, $translator);
        $key = $globalEntry->getKey();
        $locale = $globalEntry->getLocale();
        $domain = $globalEntry->getDomain();

        // Act
        $result = $manager->getTranslation($key, $domain, $locale, fallbackText: null, tenantId: $tenantId);

        // Assert
        $this->assertSame($tenantEntry->getValue(), $result, "Should return tenant-specific translation when available.");
    }

    public function testCreatesTenantSpecificEntryWhenGlobalExistsButNoTenantOverride(): void
    {
        // Arrange: repository has a global entry but none for the given tenant
        $tenantId = 'TENANT2';
        $globalEntry = $this->getSampleEntry();
        $repository = $this->getMockTranslationEntryRepository([$globalEntry]);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $cache = $this->getMockCache();
        $translator = $this->createMock(\App\Contracts\AutoTranslatorInterface::class);
        $fallbackOriginal = 'Hello'; // English original text as fallback
        $autoTranslated = 'auto-Hello';

        // Expect translator to auto-translate the original text
        $translator->expects($this->once())
                   ->method('translate')
                   ->with($fallbackOriginal, 'en', $globalEntry->getLocale())
                   ->willReturn($autoTranslated);

        // Expect a new tenant-specific entry to be persisted and flushed
        $entityManager->expects($this->once())
                      ->method('persist')
                      ->with($this->callback(function($entity) use ($globalEntry, $tenantId, $autoTranslated) {
                          /** @var TranslationEntry $entity */
                          return $entity instanceof TranslationEntry
                              && $entity->getKey() === $globalEntry->getKey()
                              && $entity->getLocale() === $globalEntry->getLocale()
                              && $entity->getDomain() === $globalEntry->getDomain()
                              && $entity->getTenantId() === $tenantId
                              && $entity->getValue() === $autoTranslated;
                      }));
        $entityManager->expects($this->once())->method('flush');

        $manager = new TranslationManager($repository, $entityManager, $cache, $translator);

        // Act
        $result = $manager->getTranslation($globalEntry->getKey(), $globalEntry->getDomain(), $globalEntry->getLocale(), $fallbackOriginal, $tenantId);

        // Assert
        $this->assertSame($autoTranslated, $result, "Should auto-translate and save a tenant-specific entry when no tenant override exists.");
    }

    public function testCreateFallbackEntryPersistsTranslation(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $repository = $this->createStub(TranslationEntryRepository::class);
        $translator = $this->createStub(AutoTranslatorInterface::class);
        $cache = $this->createStub(CacheInterface::class);

        $em->expects($this->once())->method('persist')->with($this->isInstanceOf(TranslationEntry::class));
        $em->expects($this->once())->method('flush');

        $manager = new TranslationManager($repository, $em, $cache, $translator);

        $manager->createFallbackEntry('greeting', 'Hola', 'messages', 'es', 'tenant1');
    }

}

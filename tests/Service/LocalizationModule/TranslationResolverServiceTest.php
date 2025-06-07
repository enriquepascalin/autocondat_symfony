<?php

namespace App\Tests\Service\LocalizationModule;

use App\Service\LocalizationModule\TranslationResolverService;
use App\Service\LocalizationModule\TranslationManager;
use App\Service\LocalizationModule\GoogleTranslateService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslationResolverServiceTest extends TestCase
{
    public function testTransReturnsTranslatorResultWhenAvailable(): void
    {
        $key = 'greeting';
        $translatedValue = 'Hola';
        $domain = 'messages';
        $locale = 'es';

        $translatorStub = $this->createStub(TranslatorInterface::class);
        $translatorStub->method('trans')
                       ->with($key, [], $domain, $locale)
                       ->willReturn($translatedValue);

        $googleStub = $this->createStub(GoogleTranslateService::class);
        $managerMock = $this->createMock(TranslationManager::class);

        // Google and TranslationManager should not be used if translator provides a translation
        $googleStub->expects($this->never())->method('translate');
        $managerMock->expects($this->never())->method('createFallbackEntry');

        $resolver = new TranslationResolverService($translatorStub, $managerMock, $googleStub);
        $result = $resolver->trans($key, [], $domain, $locale);

        $this->assertSame($translatedValue, $result, "Should return translator's result when available.");
    }

    public function testTransFallsBackToGoogleAndCreatesEntry(): void
    {
        $key = 'welcome';
        $domain = null;  // no domain provided, should default to 'messages'
        $locale = 'fr';

        $translatorStub = $this->createStub(TranslatorInterface::class);
        $translatorStub->method('trans')->willReturn($key); // translator returns key (no translation found)

        $googleMock = $this->createMock(GoogleTranslateService::class);
        $suggestedTranslation = 'bienvenue';
        // Expect Google Translate to be called with English source and target locale
        $googleMock->expects($this->once())
                   ->method('translate')
                   ->with($key, 'en', $locale)
                   ->willReturn($suggestedTranslation);

        $managerMock = $this->createMock(TranslationManager::class);
        // Expect a fallback entry to be created in the default 'messages' domain
        $managerMock->expects($this->once())
                    ->method('createFallbackEntry')
                    ->with($key, $suggestedTranslation, 'messages', $locale);

        $resolver = new TranslationResolverService($translatorStub, $managerMock, $googleMock);
        $result = $resolver->trans($key, [], $domain, $locale);

        $this->assertSame($suggestedTranslation, $result, "Should return Google-translated text and create a fallback entry when translator has no translation.");
    }

    public function testTransThrowsExceptionWhenTranslationFails(): void
    {
        $key = 'error.key';
        $locale = 'de';
        $domain = 'messages';

        $translatorStub = $this->createStub(TranslatorInterface::class);
        $translatorStub->method('trans')->willReturn($key);

        $googleMock = $this->createMock(GoogleTranslateService::class);
        $googleMock->method('translate')->willThrowException(new \RuntimeException("API failure"));

        $managerMock = $this->createMock(TranslationManager::class);
        $managerMock->expects($this->never())->method('createFallbackEntry');

        $resolver = new TranslationResolverService($translatorStub, $managerMock, $googleMock);

        $this->expectException(\RuntimeException::class);
        $resolver->trans($key, [], $domain, $locale);
    }
}

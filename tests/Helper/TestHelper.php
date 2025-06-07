<?php

namespace App\Tests\Helper;

use App\Entity\LocalizationModule\TranslationEntry;
use App\Entity\LocalizationModule\TranslationSourceEnum;
use App\Repository\LocalizationModule\TranslationEntryRepository;
use App\Service\LocalizationModule\GoogleTranslateService;
use App\Service\LocalizationModule\TranslationManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

trait TestHelper
{
    protected function getMockTranslationEntryRepository(array $entries = []): TranslationEntryRepository
    {
        $repo = $this->createMock(TranslationEntryRepository::class);
        $repo->method('findByKeyAndLocale')
             ->willReturnCallback(fn($key, $locale, $domain) =>
                 array_filter($entries, fn($e) =>
                     $e->getKey() === $key &&
                     $e->getLocale() === $locale &&
                     $e->getDomain() === $domain
                 )
             );
        return $repo;
    }

    protected function getMockCache(): CacheInterface
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')
              ->willReturnCallback(fn($key, $callback) => $callback($this->createMock(ItemInterface::class)));
        return $cache;
    }

    protected function getMockGoogleService(): GoogleTranslateService
    {
        $service = $this->createMock(GoogleTranslateService::class);
        $service->method('translate')
                ->willReturn('translated-text');
        return $service;
    }

    protected function getSampleEntry(): TranslationEntry
    {
        $entry = new TranslationEntry();
        $entry->setKey('hello');
        $entry->setValue('hola');
        $entry->setLocale('es');
        $entry->setDomain('messages');
        $entry->setSource(TranslationSourceEnum::MANUAL);
        return $entry;
    }
}
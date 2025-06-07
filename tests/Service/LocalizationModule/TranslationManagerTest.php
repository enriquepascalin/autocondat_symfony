<?php

namespace App\Tests\Service\LocalizationModule;

use App\Service\LocalizationModule\TranslationManager;
use PHPUnit\Framework\TestCase;
use App\Tests\Helper\TestHelper;

class TranslationManagerTest extends TestCase
{
    use TestHelper;

    public function testCreateFallbackEntryPersistsWhenMissing()
    {
        $entry = $this->getSampleEntry();
        $repository = $this->getMockTranslationEntryRepository();
        $entityManager = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);

        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $manager = new TranslationManager(
            $this->getMockGoogleService(),
            $repository,
            $entityManager
        );

        $manager->createFallbackEntry($entry->getKey(), $entry->getValue(), $entry->getDomain(), $entry->getLocale());
        $this->assertTrue(true); // If no exception, success
    }
}
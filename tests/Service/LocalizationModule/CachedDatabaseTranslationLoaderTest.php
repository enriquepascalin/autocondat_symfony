<?php

namespace App\Tests\Service\LocalizationModule;

use PHPUnit\Framework\TestCase;
use App\Service\LocalizationModule\CachedDatabaseTranslationLoader;
use Symfony\Component\Translation\MessageCatalogue;
use App\Tests\Helper\TestHelper;

class CachedDatabaseTranslationLoaderTest extends TestCase
{
    use TestHelper;

    public function testLoadReturnsMessageCatalogue()
    {
        $mockLoader = $this->createMock(\Symfony\Component\Translation\Loader\LoaderInterface::class);
        $mockLoader->method('load')
                   ->willReturn(new MessageCatalogue('es'));

        $cachedLoader = new CachedDatabaseTranslationLoader(
            $mockLoader,
            $this->getMockCache()
        );

        $catalogue = $cachedLoader->load(null, 'es', 'messages');
        $this->assertInstanceOf(MessageCatalogue::class, $catalogue);
        $this->assertEquals('es', $catalogue->getLocale());
    }
}
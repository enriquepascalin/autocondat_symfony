<?php

namespace App\Tests\EventListener;

use App\EventListener\LocaleListener;
use App\Entity\AuthenticationModule\User;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Contracts\Translation\LocaleAwareInterface;

class LocaleListenerTest extends TestCase
{
    public function testUserLocaleTakesPrecedenceOverRequest(): void
    {
        $preferredLocale = 'fr';
        // Stub user with a preferred locale
        $userStub = $this->createStub(User::class);
        $userStub->method('getLocale')->willReturn($preferredLocale);

        // Security stub returns the user
        $securityStub = $this->createStub(Security::class);
        $securityStub->method('getUser')->willReturn($userStub);

        // Prepare a Request with a different existing locale and a locale query parameter
        $request = new Request();
        $request->setLocale('en');
        $request->query->set('_locale', 'de');  // query param should be ignored due to user preference

        $translatorMock = $this->createMock(LocaleAwareInterface::class);
        $translatorMock->expects($this->once())->method('setLocale')->with($preferredLocale);

        $listener = new LocaleListener($securityStub, $this->createStub(RequestStack::class), $translatorMock);

        // Stub RequestEvent to return our Request
        $eventStub = $this->createStub(RequestEvent::class);
        $eventStub->method('getRequest')->willReturn($request);

        // Act
        $listener->onKernelRequest($eventStub);

        // Assert that request and translator locales were set to the user's locale
        $this->assertSame($preferredLocale, $request->getLocale());
    }

    public function testRequestLocaleParameterIsAppliedWhenNoUser(): void
    {
        // No authenticated user
        $securityStub = $this->createStub(Security::class);
        $securityStub->method('getUser')->willReturn(null);

        // Request with a _locale query parameter and no preset locale
        $request = new Request();
        $request->query->set('_locale', 'it');

        $translatorMock = $this->createMock(LocaleAwareInterface::class);
        $translatorMock->expects($this->once())->method('setLocale')->with('it');

        $listener = new LocaleListener($securityStub, $this->createStub(RequestStack::class), $translatorMock);
        $eventStub = $this->createStub(RequestEvent::class);
        $eventStub->method('getRequest')->willReturn($request);

        $listener->onKernelRequest($eventStub);

        $this->assertSame('it', $request->getLocale(), "Request locale should match the query parameter when no user is set.");
    }

    public function testDefaultLocaleUsedWhenNoUserAndNoParam(): void
    {
        $securityStub = $this->createStub(Security::class);
        $securityStub->method('getUser')->willReturn(null);

        $request = new Request(); // no _locale param, will use default locale
        $defaultLocale = $request->getDefaultLocale();  // e.g. 'en'

        $translatorMock = $this->createMock(LocaleAwareInterface::class);
        $translatorMock->expects($this->once())->method('setLocale')->with($defaultLocale);

        $listener = new LocaleListener($securityStub, $this->createStub(RequestStack::class), $translatorMock);
        $eventStub = $this->createStub(RequestEvent::class);
        $eventStub->method('getRequest')->willReturn($request);

        $listener->onKernelRequest($eventStub);

        $this->assertSame($defaultLocale, $request->getLocale());
    }
}
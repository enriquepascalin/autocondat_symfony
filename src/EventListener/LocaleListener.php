<?php

namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\LocaleAwareInterface;

/**
 * Sets the locale for each incoming request based on authenticated user preference or default.
 */
class LocaleListener
{
    /**
     * @param Security $security Security component to get the current user.
     * @param RequestStack $requestStack Current request stack.
     * @param LocaleAwareInterface $translator Symfony translator to set locale globally.
     */
    public function __construct(
        private readonly Security $security,
        private readonly RequestStack $requestStack,
        private readonly LocaleAwareInterface $translator,
    ) {}

    /**
     * Sets locale from authenticated user, request `_locale` or default.
     *
     * @param RequestEvent $event The kernel request event.
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // 1. Try user locale
        $user = $this->security->getUser();
        if ($user instanceof User && $user->getLocale()) {
            $locale = $user->getLocale();
        } else {
            // 2. Fallback to request _locale
            $locale = $request->getLocale() ?? $request->query->get('_locale', $request->getDefaultLocale());
        }

        // Apply locale to request and translator
        $request->setLocale($locale);
        $this->translator->setLocale($locale);
    }
}

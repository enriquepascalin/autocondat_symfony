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

namespace App\EventListener;

use App\Entity\AuthenticationModule\User;
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
     * @param Security             $security     security component to get the current user
     * @param RequestStack         $requestStack current request stack
     * @param LocaleAwareInterface $translator   symfony translator to set locale globally
     */
    public function __construct(
        private readonly Security $security,
        private readonly RequestStack $requestStack,
        private readonly LocaleAwareInterface $translator,
    ) {
    }

    /**
     * Sets locale from authenticated user, request `_locale` or default.
     *
     * @param RequestEvent $event the kernel request event
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $user = $this->security->getUser();

        if ($user instanceof User && $user->getLocale()) {
            $locale = $user->getLocale();
        } elseif ($request->query->has('_locale')) {
            $locale = $request->query->get('_locale');
        } else {
            $locale = $request->getLocale() ?? $request->query->get('_locale', $request->getDefaultLocale());
        }

        $request->setLocale($locale);
        $this->translator->setLocale($locale);
    }
}

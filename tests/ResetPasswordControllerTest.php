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

namespace App\Tests;

use App\AuthenticationModule\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $em;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        // Ensure we have a clean database
        $container = static::getContainer();

        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine')->getManager();
        $this->em = $em;

        $this->userRepository = $container->get(UserRepository::class);

        foreach ($this->userRepository->findAll() as $user) {
            $this->em->remove($user);
        }

        $this->em->flush();
    }

    public function testResetPasswordController(): void
    {
        // Create a test user
        $user = (new User())
            ->setEmail('me@example.com')
            ->setPassword('a-test-password-that-will-be-changed-later')
        ;
        $this->em->persist($user);
        $this->em->flush();

        // Test Request reset password page
        $this->client->request('GET', '/reset-password');

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Reset your password');

        // Submit the reset password form and test email message is queued / sent
        $this->client->submitForm('Send password reset email', [
            'reset_password_request_form[email]' => 'me@example.com',
        ]);

        // Ensure the reset password email was sent
        // Use either assertQueuedEmailCount() || assertEmailCount() depending on your mailer setup
        // self::assertQueuedEmailCount(1);
        self::assertEmailCount(1);

        self::assertCount(1, $messages = $this->getMailerMessages());

        self::assertEmailAddressContains($messages[0], 'from', 'mailer@autocondat.com');
        self::assertEmailAddressContains($messages[0], 'to', 'me@example.com');
        self::assertEmailTextBodyContains($messages[0], 'This link will expire in 1 hour.');

        self::assertResponseRedirects('/reset-password/check-email');

        // Test check email landing page shows correct "expires at" time
        $crawler = $this->client->followRedirect();

        self::assertPageTitleContains('Password Reset Email Sent');
        self::assertStringContainsString('This link will expire in 1 hour', $crawler->html());

        // Test the link sent in the email is valid
        $email = $messages[0]->toString();
        preg_match('#(/reset-password/reset/[a-zA-Z0-9]+)#', $email, $resetLink);

        $this->client->request('GET', $resetLink[1]);

        self::assertResponseRedirects('/reset-password/reset');

        $this->client->followRedirect();

        // Test we can set a new password
        $this->client->submitForm('Reset password', [
            'change_password_form[plainPassword][first]' => 'newStrongPassword',
            'change_password_form[plainPassword][second]' => 'newStrongPassword',
        ]);

        self::assertResponseRedirects('/admin');

        $user = $this->userRepository->findOneBy(['email' => 'me@example.com']);

        self::assertInstanceOf(User::class, $user);

        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        self::assertTrue($passwordHasher->isPasswordValid($user, 'newStrongPassword'));
    }
}

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

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('BackofficeModule/admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Autocondat7');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('admin.tab.dashboard', 'fa fa-home');

        yield MenuItem::section('admin.tab.authentication');
        yield MenuItem::linkToCrud('admin.tab.users', 'fas fa-users', \App\AuthenticationModule\Entity\User::class);
        yield MenuItem::linkToCrud('admin.tab.roles', 'fas fa-user-tag', \App\AuthenticationModule\Entity\Role::class);
        yield MenuItem::linkToCrud('admin.tab.permissions', 'fas fa-building', \App\AuthenticationModule\Entity\Permission::class);
        yield MenuItem::linkToCrud('admin.tab.consentlog', 'fas fa-users-cog', \App\AuthenticationModule\Entity\ConsentLog::class);
        yield MenuItem::linkToCrud('admin.tab.sessions', 'fas fa-user-check', \App\AuthenticationModule\Entity\Session::class);

        yield MenuItem::section('admin.tab.multitenancy');
        yield MenuItem::linkToCrud('admin.tab.tenants', 'fas fa-building', \App\MultitenancyModule\Entity\Tenant::class);
        yield MenuItem::linkToCrud('admin.tab.segments', 'fas fa-building', \App\MultitenancyModule\Entity\Segment::class);
        yield MenuItem::linkToCrud('admin.tab.tenantauditlogs', 'fas fa-building', \App\MultitenancyModule\Entity\TenantAuditLog::class);
        yield MenuItem::linkToCrud('admin.tab.tenantconfig', 'fas fa-building', \App\MultitenancyModule\Entity\TenantConfig::class);
        yield MenuItem::linkToCrud('admin.tab.tenantusers', 'fas fa-users', \App\MultitenancyModule\Entity\TenantUsers::class);

        yield MenuItem::section('');
        //yield MenuItem::linkToLogout('Logout', 'fa fa-exit');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        // Usually it's better to call the parent method because that gives you a
        // user menu with some menu items already created ("sign out", "exit impersonation", etc.)
        // if you prefer to create the user menu from scratch, use: return UserMenu::new()->...
        return parent::configureUserMenu($user)
            // use the given $user object to get the user name
            ->setName($user->getFullName())
            // use this method if you don't want to display the name of the user
            ->displayUserName(false)

            // you can return an URL with the avatar image
            ->setAvatarUrl('https://uxwing.com/avatar-icon/')
            //->setAvatarUrl($user->getProfileImageUrl())
            // use this method if you don't want to display the user image
            //->displayUserAvatar(false)
            // you can also pass an email address to use gravatar's service
            ->setGravatarEmail($user->getEmail())

            // you can use any type of menu item, except submenus
            ->addMenuItems([
                MenuItem::linkToRoute('admin.routes.myprofile', 'fa fa-id-card', '...', ['...' => '...']),
                MenuItem::linkToRoute('admin.routes.settings', 'fa fa-user-cog', '...', ['...' => '...']),
                MenuItem::section(),
                MenuItem::linkToLogout('admin.routes.logout', 'fa fa-sign-out'),
            ]);
    }
}
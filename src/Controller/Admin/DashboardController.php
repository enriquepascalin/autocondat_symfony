<?php

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
        return $this->render('admin/index.html.twig');
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
        yield MenuItem::linkToCrud('admin.tab.users', 'fas fa-users', \App\Entity\AuthenticationModule\User::class);
        yield MenuItem::linkToCrud('admin.tab.roles', 'fas fa-user-tag', \App\Entity\AuthenticationModule\Role::class);
        yield MenuItem::linkToCrud('admin.tab.permissions', 'fas fa-building', \App\Entity\AuthenticationModule\Permission::class);
        yield MenuItem::linkToCrud('admin.tab.consentlog', 'fas fa-users-cog', \App\Entity\AuthenticationModule\ConsentLog::class);
        yield MenuItem::linkToCrud('admin.tab.sessions', 'fas fa-user-check', \App\Entity\AuthenticationModule\Session::class);

        yield MenuItem::section('admin.tab.multitenancy');
        yield MenuItem::linkToCrud('admin.tab.tenants', 'fas fa-building', \App\Entity\MultitenancyModule\Tenant::class);
        yield MenuItem::linkToCrud('admin.tab.segments', 'fas fa-building', \App\Entity\MultitenancyModule\Segment::class);
        yield MenuItem::linkToCrud('admin.tab.tenantauditlogs', 'fas fa-building', \App\Entity\MultitenancyModule\TenantAuditLog::class);
        yield MenuItem::linkToCrud('admin.tab.tenantconfig', 'fas fa-building', \App\Entity\MultitenancyModule\TenantConfig::class);
        yield MenuItem::linkToCrud('admin.tab.tenantusers', 'fas fa-users', \App\Entity\MultitenancyModule\TenantUsers::class);

        yield MenuItem::section('');
        yield MenuItem::linkToLogout('Logout', 'fa fa-exit');
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

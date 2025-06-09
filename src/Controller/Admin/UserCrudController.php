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
 * @version 1.0.0
 * @since 2025-06-01
 * @license license.md
 */

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\AuthenticationModule\User;
use App\Entity\AuthenticationModule\RolesEnum;
use App\Entity\AuthenticationModule\UserStatusEnum;
use App\Entity\AuthenticationModule\UserRole;
use App\Entity\MultitenancyModule\Tenant;
use App\Traits\TenantAwareTrait;
use App\Traits\BlameableTrait;
use App\Traits\SoftDeletableTrait;
use App\Traits\TimestampableTrait;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PasswordField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Doctrine\ORM\EntityManagerInterface;

/**
 * UserCrudController is responsible for managing the CRUD operations for the User entity.
 * It extends the AbstractCrudController from EasyAdminBundle.
 * 
 * @package App\Controller\Admin
 */
class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    { }

    /**
     * Get the fully qualified class name of the entity this controller manages.
     * 
     * @return string The fully qualified class name of the User entity.
     */
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    /** 
     * Configure the CRUD settings for the User entity.
     * 
     * @param Crud $crud
     * @return Crud
     */
    public function congfigureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('admin.user.singular')
            ->setEntityLabelInPlural('admin.user.plural')
            ->setPageTitle(Crud::PAGE_INDEX, 'admin.user.index.title')
            ->setPageTitle(Crud::PAGE_DETAIL, 'admin.user.detail.title')
            ->setPageTitle(Crud::PAGE_EDIT, 'admin.user.edit.title')
            ->setPageTitle(Crud::PAGE_NEW, 'admin.user.new.title')
            ->setSearchFields([
                'email',
                'role',
            ])
            ->setPaginatorPageSize(30)
            ->setDefaultSort(['createdAt' => 'DESC'])
        ;
    }

    /**
     * Configure the actions available in the CRUD interface.
     * 
     * @param string $pageName The name of the page being configured.
     * @return iterable The list of actions to be displayed.
     */
    public function configureFields(string $pageName): iterable
    {
        yield FormField::addTab('admin.user.tab.general');
        yield IdField::new('id', 'admin.user.field.id')
            ->onlyOnDetail();
        yield FormField::addPanel('admin.user.panel.credentials')
            ->setIcon('fa fa-key');
        yield EmailField::new('email', 'admin.user.field.email')
            ->setRequired(true)
            ->setColumns(6);
         yield TextField::new('password', 'admin.user.field.password')
            ->setFormType(PasswordType::class)
            ->onlyOnForms()
            ->setRequired($pageName === Crud::PAGE_NEW)
            ->setHelp('admin.user.help.password')
            ->setColumns(6);       
        yield FormField::addPanel('admin.user.panel.profile')
            ->setIcon('fa fa-user');
        yield ChoiceField::new('status', 'admin.user.field.status')
            ->setChoices(UserStatusEnum::cases())
            ->setRequired(true);
        yield ChoiceField::new('role', 'admin.user.field.role')
            ->setChoices(RolesEnum::cases())
            ->setRequired(true);
        yield AssociationField::new('tenant', 'admin.user.field.tenant')
            ->autocomplete()
            ->setRequired(true)
            ->setCrudController(TenantCrudController::class)
            ->setColumns(6);
        yield FormField::addTab('admin.tab.metadata')
            ->setIcon('fa fa-history');
        yield DateTimeField::new('createdAt', 'admin.field.created_at')
            ->onlyOnDetail();
        yield DateTimeField::new('updatedAt', 'admin.field.updated_at')
            ->onlyOnDetail();
        yield DateTimeField::new('deletedAt', 'admin.field.deleted_at')
            ->onlyOnDetail();
    }

    /**
     * Persist the entity instance to the database.
     * 
     *  @param EntityManagerInterface $entityManager The entity manager to use for persistence.
     *  @param mixed $entityInstance The instance of the entity to persist.
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->handlePassword($entityInstance);
        $this->handleTenantContext($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    /**
     * Update the entity instance in the database.
     * 
     * @param EntityManagerInterface $entityManager The entity manager to use for updating.
     * @param mixed $entityInstance The instance of the entity to update.
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->handlePassword($entityInstance);
        $this->handleTenantContext($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    /**
     * Handle the password for the User entity.
     * 
     * @param User $user The User entity instance.
     */
    private function handlePassword(User $user): void
    {
        $plainPassword = $user->getPassword();
        if ($plainPassword !== null) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        }
    }

    /**
     * Handle the tenant context for the User entity.
     * 
     * @param User $user The User entity instance.
     */
    private function handleTenantContext(User $user): void
    {
        if (in_array(TenantAwareTrait::class, class_uses($user), true)) {
            if ($user->getTenant() === null) {
                $user->setTenant($this->getUser()->getTenant());
            }
        }
    }

}

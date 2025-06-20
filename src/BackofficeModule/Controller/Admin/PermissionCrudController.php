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

namespace App\BackofficeModule\Controller\Admin;

use App\AuthenticationModule\Entity\Permission;
use App\AuthenticationModule\Entity\Role;
use App\AuthenticationModule\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * CRUD controller for Permission management.
 *
 * @extends AbstractCrudController<Permission>
 */
final class PermissionCrudController extends AbstractCrudController
{
    /**
     * Returns the fully qualified class name of the entity managed by this CRUD controller.
     *
     * @return class-string<Permission>
     */
    public static function getEntityFqcn(): string
    {
        return Permission::class;
    }

    /**
     * Configures the CRUD settings.
     *
     * @param Crud $crud The CRUD configuration object
     *
     * @return Crud
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'admin.permission.index_title')
            ->setPageTitle(Crud::PAGE_NEW, 'admin.permission.new_title')
            ->setPageTitle(Crud::PAGE_EDIT, 'admin.permission.edit_title')
            ->setPageTitle(Crud::PAGE_DETAIL, 'admin.permission.detail_title')
            ->setEntityLabelInSingular('admin.permission.singular')
            ->setEntityLabelInPlural('admin.permission.plural')
            ->setSearchFields(['name', 'description'])
            ->setDefaultSort(['name' => 'ASC']);
    }

    /**
     * Configures the actions available for this CRUD controller.
     *
     * @param Actions $actions The actions configuration object
     *
     * @return Actions
     */
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DELETE, static function (Action $action): Action {
                return $action->setIcon('fa fa-trash')->setLabel(false);
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, static function (Action $action): Action {
                return $action->setIcon('fa fa-pencil')->setLabel(false);
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, static function (Action $action): Action {
                return $action->setIcon('fa fa-eye')->setLabel(false);
            });
    }

    /**
     * Configures the fields to be displayed in the CRUD interface.
     *
     * @param string $pageName The name of the current page (index, detail, new, edit)
     *
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        yield FormField::addTab('admin.permission.tab.general');

        yield IdField::new('id', 'admin.permission.field.id')
            ->onlyOnDetail();

        yield FormField::addPanel('admin.permission.panel.definition')
            ->setIcon('fa fa-key');

        yield TextField::new('name', 'admin.permission.field.name')
            ->setRequired(true)
            ->setColumns(6);

        yield TextField::new('description', 'admin.permission.field.description')
            ->setColumns(6);

        yield FormField::addPanel('admin.permission.panel.status')
            ->setIcon('fa fa-toggle-on');

        yield BooleanField::new('isActive', 'admin.permission.field.is_active')
            ->renderAsSwitch($pageName !== Crud::PAGE_INDEX)
            ->setColumns(6);

        yield BooleanField::new('isSystem', 'admin.permission.field.is_system')
            ->renderAsSwitch($pageName !== Crud::PAGE_INDEX)
            ->setColumns(6)
            ->setHelp('admin.permission.help.is_system');

        yield FormField::addPanel('admin.permission.panel.relationships')
            ->setIcon('fa fa-link');

        yield AssociationField::new('roles', 'admin.permission.field.roles')
            ->setCrudController(RoleCrudController::class)
            ->autocomplete()
            ->setColumns(6);

        yield AssociationField::new('users', 'admin.permission.field.users')
            ->setCrudController(UserCrudController::class)
            ->autocomplete()
            ->setColumns(6);

        // Metadata tab
        yield FormField::addTab('admin.permission.tab.metadata')
            ->setIcon('fa fa-database');

        yield DateTimeField::new('createdAt', 'admin.permission.field.created_at')
            ->onlyOnDetail();

        yield DateTimeField::new('updatedAt', 'admin.permission.field.updated_at')
            ->onlyOnDetail();

        yield DateTimeField::new('deletedAt', 'admin.permission.field.deleted_at')
            ->onlyOnDetail();
    }

    /**
     * Handles entity persistence.
     *
     * @param EntityManagerInterface $entityManager The entity manager
     * @param Permission             $entityInstance The permission entity instance
     */
    public function persistEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        $this->handleTimestamps($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    /**
     * Handles entity update.
     *
     * @param EntityManagerInterface $entityManager The entity manager
     * @param Permission             $entityInstance The permission entity instance
     */
    public function updateEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        $this->handleTimestamps($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    /**
     * Handles timestamp fields.
     *
     * @param Permission $permission The permission entity
     */
    private function handleTimestamps(Permission $permission): void
    {
        $now = new \DateTimeImmutable();
        if ($permission->getCreatedAt() === null) {
            $permission->setCreatedAt($now);
        }
        $permission->setUpdatedAt($now);
    }
}
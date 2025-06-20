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

use App\AuthenticationModule\Entity\Session;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;

class SessionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Session::class;
    }

    /**
     * Configure the CRUD settings for the Session entity.
     *
     * @param Crud $crud the CRUD configuration object
     *
     * @return Crud the configured CRUD object
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('admin.session.singular')
            ->setEntityLabelInPlural('admin.session.plural')
            ->setPageTitle(Crud::PAGE_INDEX, 'admin.session.index.title')
            ->setPageTitle(Crud::PAGE_DETAIL, 'admin.session.detail.title')
            ->setPageTitle(Crud::PAGE_EDIT, 'admin.session.edit.title')
            ->setPageTitle(Crud::PAGE_NEW, 'admin.session.new.title')
            ->setSearchFields(['token', 'ipAdress', 'userAgent'])
            ->setPaginatorPageSize(30)
            ->setDefaultSort(['createdAt' => 'DESC'])
        ;
    }

    /**
     * Configure the actions available in the CRUD interface.
     *
     * @param Actions $actions the actions configuration object
     *
     * @return Actions the configured actions object
     */
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-pencil');
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setIcon('fa fa-eye');
            })
        ;
    }

    /**
     * Configure the fields to be displayed in the CRUD interface.
     *
     * @param string $pageName the name of the page being configured
     *
     * @return iterable<FieldInterface> the list of fields to be displayed
     */
    public function configureFields(string $pageName): iterable
    {
        yield FormField::addTab('admin.tab.general')->setIcon('fa fa-cog');
        yield IdField::new('id', 'admin.session.field.id')
            ->hideOnForm()
            ->setColumns(12);
        yield FormField::addPanel('admin.session.panel.general')
            ->setIcon('fa fa-clock');
        yield AssociationField::new('autocondatUser', 'admin.session.field.autocondat_user')
            ->autocomplete()
            ->setCrudController(UserCrudController::class)
            ->setColumns(6);
        yield TextField::new('token', 'admin.session.field.token')
            ->setRequired(true)
            ->setColumns(6);
        yield DateTimeField::new('expiresAt', 'admin.session.field.expires_at')
            ->setRequired(true)
            ->setFormat('yyyy.MM.dd G HH:mm:ss zzz')
            ->setColumns(6);
        yield DateTimeField::new('createdAt', 'admin.field.created_at')
            ->hideOnIndex()
            ->setRequired(true)
            ->setFormat('yyyy.MM.dd G HH:mm:ss zzz')
            ->setColumns(6);
        yield DateTimeField::new('lastUsed', 'admin.session.field.last_used')
            ->hideOnIndex()
            ->setRequired(false)
            ->setFormat('yyyy.MM.dd G HH:mm:ss zzz')
            ->setColumns(6);
        yield TextField::new('ipAdress', 'admin.session.field.ip_adress')
            ->setRequired(false)
            ->setColumns(6);
        yield TextField::new('userAgent', 'admin.session.field.user_agent')
            ->hideOnIndex()
            ->setRequired(false)
            ->setColumns(6);
        yield BooleanField::new('isRevoked', 'admin.session.field.is_revoked')
            ->setRequired(true)
            ->setColumns(6);
        yield TextField::new('userAgentHash', 'admin.session.field.user_agent_hash')
            ->hideOnIndex()
            ->setRequired(false)
            ->setColumns(6);

        /*
         * Trait implementations for Metadata and Audit Logs
         */
        yield FormField::addTab('admin.tab.metadata')
            ->setIcon('fa fa-history');
        yield AssociationField::new('tenant', 'admin.user.field.tenant')
            ->autocomplete()
            ->hideOnIndex()
            ->setRequired(false)
            ->setCrudController(TenantCrudController::class)
            ->setColumns(12);
        yield DateTimeField::new('createdAt', 'admin.field.created_at')
            ->hideOnIndex()
            ->setColumns(6);
        yield DateTimeField::new('updatedAt', 'admin.field.updated_at')
            ->hideOnIndex()
            ->setColumns(6);
        yield AssociationField::new('createdBy', 'admin.field.created_by')
            ->hideOnIndex()
            ->autocomplete()
            ->setCrudController($this::class)
            ->setColumns(6);
        yield AssociationField::new('updatedBy', 'admin.field.updated_by')
            ->hideOnIndex()
            ->autocomplete()
            ->setCrudController($this::class)
            ->setColumns(6);
    }
}

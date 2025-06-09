<?php

namespace App\Controller\Admin;

use App\Entity\AuthenticationModule\User;
use App\Entity\AuthenticationModule\RolesEnum;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function congfigureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('Users')
            ->setPageTitle(Crud::PAGE_INDEX, 'User Management')
            ->setPageTitle(Crud::PAGE_EDIT, 'Edit User')
            ->setPageTitle(Crud::PAGE_NEW, 'Create New User')
            ->setSearchFields([
                'email',
                'role',
            ])
            ->setPaginatorPageSize(30)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('email')
                ->setLabel('Email Address')
                ->setRequired(true),
            ChoiceField::new('role', 'Role')
                ->setChoices(RolesEnum::cases()),
        ];
    }

}

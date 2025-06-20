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

use App\AuthenticationModule\Entity\User;
use App\AuthenticationModule\Entity\RolesEnum;
use App\AuthenticationModule\Entity\UserStatusEnum;
use App\MultitenancyModule\Entity\Tenant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly Security $security,
    ) {
    }
    
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    /**
     * Configure the CRUD settings for the User entity.
     *
     * @param Crud $crud the CRUD configuration object
     *
     * @return Crud the configured CRUD object
     */
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('admin.user.singular')
            ->setEntityLabelInPlural('admin.user.plural')
            ->setPageTitle(Crud::PAGE_INDEX, 'admin.user.index.title')
            ->setPageTitle(Crud::PAGE_DETAIL, 'admin.user.detail.title')
            ->setPageTitle(Crud::PAGE_EDIT, 'admin.user.edit.title')
            ->setPageTitle(Crud::PAGE_NEW, 'admin.user.new.title')
            ->setSearchFields(['email', 'role',])
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
            });
    }

    /**
     * Configure the actions available in the CRUD interface.
     *
     * @param string $pageName the name of the page being configured
     *
     * @return iterable<FieldInterface> the list of fields to be displayed (needed to add context to yield)
     */
    public function configureFields(string $pageName): iterable
    {
        yield FormField::addTab('admin.tab.general')
            ->setIcon('fa fa-cog');
        yield IdField::new('id', 'admin.user.field.id')
            ->hideOnForm()
            ->setColumns(12);
        yield FormField::addPanel('admin.user.panel.credentials')
            ->setIcon('fa fa-key');
        yield EmailField::new('email', 'admin.user.field.email')
            ->setRequired(true)
            ->setColumns(12);
        yield FormField::addPanel('admin.user.panel.profile')
            ->setIcon('fa fa-user');
        yield ChoiceField::new('status', 'admin.user.field.status')
            ->setChoices(UserStatusEnum::cases())
            ->setRequired(true)
            ->setColumns(6);
        yield ChoiceField::new('role', 'admin.user.field.role')
            ->setChoices(RolesEnum::cases())
            ->setRequired(true)
            ->setColumns(6);
        yield TextField::new('locale', 'admin.user.field.locale')
            ->setHelp('admin.user.help.locale')
            ->setColumns(6);
        yield BooleanField::new('isVerified', 'admin.user.field.is_verified')
            ->setHelp('admin.user.help.is_verified')
            ->setColumns(6);
        yield FormField::addPanel('admin.user.security')
            ->setIcon('fa fa-key');
        yield TextField::new('password', 'admin.user.field.password')
           ->setFormType(PasswordType::class)
           ->onlyOnForms()
           ->setRequired(Crud::PAGE_NEW === $pageName)
           ->setHelp('admin.user.help.password')
           ->setColumns(6);
        yield TextField::new('mfaSecret', 'admin.user.field.mfa_secret')
           ->setFormType(PasswordType::class)
           ->onlyOnForms()
           ->setRequired(false)
           ->setHelp('admin.user.help.mfa_secret')
           ->setColumns(6);
        yield BooleanField::new('isMfaEnabled', 'admin.user.field.is_mfa_enabled')
            ->setHelp('admin.user.help.is_mfa_enabled')
            ->onlyOnForms()
            ->setColumns(6);
        yield TextField::new('passwordResetToken', 'admin.user.field.password_reset_token')
            ->setFormType(PasswordType::class)
            ->onlyOnForms()
            ->setRequired(false)
            ->setHelp('admin.user.help.password_reset_token')
            ->setColumns(6);
        yield DateTimeField::new('passwordResetExpiresAt', 'admin.user.field.password_reset_expires_at')
            ->setFormat('yyyy.MM.dd G HH:mm:ss zzz')
            ->onlyOnForms()
            ->setColumns(6);
        yield ArrayField::new('mfaBackupCodes', 'admin.user.field.mfa_backup_codes')
            ->onlyOnForms()
            ->setHelp('admin.user.help.mfa_backup_codes')
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

        /*
         * OneToMany and ManyToMany relations
         */
        yield FormField::addTab('admin.tab.details')
            ->setIcon('fa fa-table');
        yield FormField::addPanel('admin.user.panel.sessions')
            ->setIcon('fa fa-key');
        yield AssociationField::new('sessions', 'admin.user.field.sessions')
            ->autocomplete()
            ->setCrudController(SessionCrudController::class)
            ->setColumns(12);
        yield FormField::addPanel('admin.user.panel.sessions')
            ->setIcon('fa fa-key');
        yield AssociationField::new('consentLogs', 'admin.user.field.consent_logs')
            ->autocomplete()
            ->setCrudController(ConsentLogCrudController::class)
            ->setColumns(12);
        yield FormField::addPanel('admin.user.panel.segments')
            ->setIcon('fa fa-key');
        yield AssociationField::new('segments', 'admin.user.field.segments')
            ->autocomplete()
            ->setCrudController(SegmentCrudController::class)
            ->setColumns(12);
        /* TODO Relations for not yet implemented admin crud controllers */
        /*
        yield FormField::addPanel('admin.user.panel.asigned_project_phases')
            ->setIcon('fa fa-key');
        yield AssociationField::new('asignedProjectPhases', 'admin.user.field.asigned_project_phases')
            ->autocomplete()
            ->setCrudController(AssignmentProjectPhasesCrudController::class);
        yield FormField::addPanel('admin.user.panel.project_phase_assignments')
            ->setIcon('fa fa-key');
        yield AssociationField::new('projectPhaseAssignments', 'admin.user.field.project_phase_assignments')
            ->autocomplete()
            ->setCrudController(AssignmentProjectPhasesCrudController::class);
        yield FormField::addPanel('admin.user.panel.audiences')
            ->setIcon('fa fa-key');
        yield AssociationField::new('audiences', 'admin.user.field.audiences')
            ->autocomplete()
            ->setCrudController(AssignmentProjectPhasesCrudController::class);
        yield FormField::addPanel('admin.user.panel.tickets')
            ->setIcon('fa fa-key');
        yield AssociationField::new('audiences', 'admin.user.field.tickets')
            ->autocomplete()
            ->setCrudController(TicketCrudController::class);
        yield FormField::addPanel('admin.user.panel.licenses')
            ->setIcon('fa fa-key');
        yield AssociationField::new('audiences', 'admin.user.field.licenses')
            ->autocomplete()
            ->setCrudController(LicenseCrudController::class);
         */
    }

    /**
     * Persist the entity instance to the database.
     *
     * @param EntityManagerInterface $entityManager  the entity manager to use for persistence
     * @param mixed                  $entityInstance the instance of the entity to persist
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) {
            throw new \InvalidArgumentException(sprintf('Expected instance of %s, got %s', User::class, is_object($entityInstance) ? get_class($entityInstance) : gettype($entityInstance)));
        }
        $this->handlePassword($entityInstance, $entityManager);
        $this->handleTenantContext($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    /**
     * Update the entity instance in the database.
     *
     * @param EntityManagerInterface $entityManager  the entity manager to use for updating
     * @param mixed                  $entityInstance the instance of the entity to update
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) {
            throw new \InvalidArgumentException(sprintf('Expected instance of %s, got %s', User::class, is_object($entityInstance) ? get_class($entityInstance) : gettype($entityInstance)));
        }
        $this->handlePassword($entityInstance, $entityManager);
        $this->handleTenantContext($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    /**
     * Handle the password for the User entity.
     *
     * @param User $user the User entity instance
     */
    private function handlePassword(User $user, EntityManagerInterface $em): void
    {
        $plainPassword = $user->getPassword();
        if (null !== $plainPassword && '' !== trim($plainPassword)) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        } else {
            $existingUser = $em->getRepository(User::class)->find($user->getId());
            if ($existingUser && ($existingPassword = $existingUser->getPassword())) {
                $user->setPassword($existingPassword);
            }
        }
    }

    /**
     * Handle the tenant context for the User entity.
     *
     * @param User $user the User entity instance
     */
    private function handleTenantContext(User $user): void
    {
        if (null !== $user->getTenant()) {
            return;
        }

        $currentUser = $this->security->getUser();
        if ($currentUser instanceof User && null !== $currentUser->getTenant()) {
            $user->setTenant($currentUser->getTenant());
        }
    }
}

<?php

namespace App\Controller\Admin;

use App\AuditTrailModule\Entity\AuditObjectMeta;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AuditObjectMetaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AuditObjectMeta::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}

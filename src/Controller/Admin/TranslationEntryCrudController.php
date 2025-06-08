<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\LocalizationModule\TranslationEntry;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class TranslationEntryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TranslationEntry::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('locale'),
            TextField::new('domain'),
            TextField::new('key'),
            TextField::new('value'),
            TextField::new('tenantId'),
            ChoiceField::new('source')
                ->setChoices([
                    'Manual' => 0,
                    'Automatic' => 1,
                    'Imported' => 2,
                ]),
        ];
    }
}

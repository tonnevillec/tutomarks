<?php

namespace App\Controller\Admin;

use App\Entity\Hebdoo;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HebdooCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Hebdoo::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('Title'),
            TextField::new('url'),
            TextField::new('pseudo'),
            AssociationField::new('tags'),
            AssociationField::new('language'),

            DateTimeField::new('created_at')->onlyOnIndex(),
        ];
    }
}

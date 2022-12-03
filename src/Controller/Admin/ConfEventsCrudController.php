<?php

namespace App\Controller\Admin;

use App\Entity\ConfEvents;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ConfEventsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ConfEvents::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('year'),
            AssociationField::new('conference'),
            AssociationField::new('playlist'),
        ];
    }
}

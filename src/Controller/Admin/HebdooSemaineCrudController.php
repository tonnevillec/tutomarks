<?php

namespace App\Controller\Admin;

use App\Entity\HebdooSemaine;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HebdooSemaineCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HebdooSemaine::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('Title'),
            TextField::new('Youtube'),
            BooleanField::new('is_publish'),
            AssociationField::new('ressources'),

            DateTimeField::new('created_at')->onlyOnIndex(),
        ];
    }
}

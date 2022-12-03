<?php

namespace App\Controller\Admin;

use App\Entity\Concours;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ConcoursCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Concours::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            TextField::new('hashtag'),
            BooleanField::new('isOpen'),
            TextField::new('postTwitter'),
            TextField::new('videoYoutube'),
            AssociationField::new('participants')->onlyOnIndex(),

            DateTimeField::new('created_at')->onlyOnIndex(),
            DateTimeField::new('ended_at'),
        ];
    }
}

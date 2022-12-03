<?php

namespace App\Controller\Admin;

use App\Entity\Conferences;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ConferencesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Conferences::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            TextField::new('youtubeurl'),
            TextField::new('websiteurl'),
            TextField::new('logo'),
            AssociationField::new('events'),
        ];
    }
}

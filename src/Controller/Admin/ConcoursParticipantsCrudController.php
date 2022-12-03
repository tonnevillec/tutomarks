<?php

namespace App\Controller\Admin;

use App\Entity\ConcoursParticipants;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ConcoursParticipantsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ConcoursParticipants::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('twitterAccount'),
            AssociationField::new('concours'),

            DateTimeField::new('created_at')->onlyOnIndex(),
        ];
    }
}

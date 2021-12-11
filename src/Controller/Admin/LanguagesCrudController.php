<?php

namespace App\Controller\Admin;

use App\Entity\Languages;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class LanguagesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Languages::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
            TextField::new('shortname'),
            DateTimeField::new('updated_at')->onlyOnIndex(),
//            AssociationField::new('links')->onlyOnIndex(),
            TextField::new('imageFile')->setFormType(VichImageType::class)->hideOnIndex(),
            ImageField::new('logo')->setBasePath('/uploads/images/')->onlyOnIndex(),
        ];
    }
}

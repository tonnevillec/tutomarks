<?php

namespace App\Controller\Admin;

use App\Entity\Authors;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AuthorsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Authors::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            ImageField::new('logo')->setBasePath('/uploads/images/')->onlyOnIndex(),
            TextField::new('yt_logo')->hideOnIndex(),
            TextField::new('title'),
            TextEditorField::new('description'),
            UrlField::new('site_url'),
            UrlField::new('twitter'),
            UrlField::new('github'),
            UrlField::new('twitch'),
            UrlField::new('youtube'),
            DateTimeField::new('updated_at')->onlyOnIndex(),
            AssociationField::new('links')->onlyOnIndex(),
            TextField::new('imageFile')->setFormType(VichImageType::class)->hideOnIndex(),
        ];
    }
}

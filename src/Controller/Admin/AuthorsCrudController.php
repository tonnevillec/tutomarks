<?php

namespace App\Controller\Admin;

use App\Entity\Authors;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
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
        yield IdField::new('id')->onlyOnIndex();
        yield ImageField::new('logo')
                ->setBasePath('/uploads/images/')
                ->onlyOnIndex();
//        yield TextField::new('yt_logo')
//            ->hideOnIndex();
        yield TextField::new('title');
        yield TextEditorField::new('description');
        yield UrlField::new('site_url');
        yield UrlField::new('twitter');
        yield UrlField::new('github');
        yield UrlField::new('twitch');
        yield UrlField::new('youtube');
        yield DateTimeField::new('updated_at')->onlyOnIndex();
        yield AssociationField::new('links')->onlyOnIndex();
        yield AssociationField::new('attachment')->hideOnIndex();
//        yield TextField::new('imageFile')
//                ->setFormType(VichImageType::class)
//                ->hideOnIndex();

        $updatedAt = DateTimeField::new('updated_at')->setFormTypeOptions([
            'html5' => true,
            'years' => range(date('Y'), date('Y') + 5),
            'widget' => 'single_text',
        ]);
        if (Crud::PAGE_EDIT === $pageName) {
            yield $updatedAt->setFormTypeOption('disabled', true);
        } else {
            yield $updatedAt;
        }
    }
}

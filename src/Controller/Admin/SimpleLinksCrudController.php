<?php

namespace App\Controller\Admin;

use App\Entity\SimpleLinks;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SimpleLinksCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SimpleLinks::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('title');
        yield AssociationField::new('author');
        yield UrlField::new('url');
        yield AssociationField::new('category');
        yield AssociationField::new('tags');
        yield AssociationField::new('language');
        yield TextField::new('imageFile')->setFormType(VichImageType::class)->hideOnIndex();
        yield ImageField::new('image')->setBasePath('/uploads/images/')->onlyOnIndex();
        yield BooleanField::new('is_publish');

        $publishedAt = DateTimeField::new('published_at')->setFormTypeOptions([
            'html5' => true,
            'years' => range(date('Y'), date('Y') + 5),
            'widget' => 'single_text',
        ]);
        if (Crud::PAGE_EDIT === $pageName) {
            yield $publishedAt->setFormTypeOption('disabled', true);
        } else {
            yield $publishedAt;
        }

        yield TextEditorField::new('description')->hideOnIndex();

    }
}

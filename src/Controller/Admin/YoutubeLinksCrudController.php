<?php

namespace App\Controller\Admin;

use App\Entity\YoutubeLinks;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
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

class YoutubeLinksCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return YoutubeLinks::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('title');
        yield TextField::new('youtube_id');
        yield TextField::new('img_small')->hideOnIndex();
        yield TextField::new('img_medium')->hideOnIndex();
        yield TextField::new('img_large')->hideOnIndex();
        yield AssociationField::new('author');
        yield UrlField::new('url');
        yield AssociationField::new('category');
        yield AssociationField::new('tags');
        yield AssociationField::new('language');
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

//        yield ImageField::new('img_small')->hideOnIndex();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW)
        ;
    }
}

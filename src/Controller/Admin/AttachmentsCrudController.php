<?php

namespace App\Controller\Admin;

use App\Entity\Attachments;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AttachmentsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Attachments::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield ImageField::new('image')
            ->setBasePath('/uploads/images/')
            ->onlyOnIndex();
        yield TextField::new('imageFile')
                ->setFormType(VichImageType::class)
                ->hideOnIndex();

        yield DateTimeField::new('updated_at')
            ->onlyOnIndex()
        ;
//        $updatedAt = DateTimeField::new('updated_at')->setFormTypeOptions([
//            'html5' => true,
//            'years' => range(date('Y'), date('Y') + 5),
//            'widget' => 'single_text',
//        ]);
//        if (Crud::PAGE_EDIT === $pageName) {
//            yield $updatedAt->setFormTypeOption('disabled', true);
//        } else {
//            yield $updatedAt;
//        }
    }
}

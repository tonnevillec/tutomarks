<?php

namespace App\Controller\Admin;

use App\Entity\ConfEventPlaylist;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ConfEventPlaylistCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ConfEventPlaylist::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}

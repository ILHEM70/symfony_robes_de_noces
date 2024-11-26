<?php

namespace App\Controller\Admin;

use App\Entity\Produits;
use App\Entity\Taille;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProduitsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produits::class;
    }

   
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom_du_produit'),
            TextEditorField::new('description'),
            NumberField::new('prix'),
            AssociationField::new('taille'),
            AssociationField::new('couleur'),
            ImageField::new('image')->setBasePath('assets/images/')->setUploadDir('public/assets/images/')->setRequired(false),

        ];
    }
   
}

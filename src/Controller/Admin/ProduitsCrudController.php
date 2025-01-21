<?php

namespace App\Controller\Admin;

use App\Entity\Produits;
use App\Form\ImagesType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
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
            TextAreaField::new('description')->hideOnDetail(),
            NumberField::new('prix'),
            CollectionField::new('images')
                ->setEntryType(ImagesType::class)
                ->setFormTypeOptions([
                    'by_reference' => false,
                    
                ])
                ->setEntryIsComplex(true)
                ->showEntryLabel(false)
                ->onlyOnForms(),
            AssociationField::new('taille'),
            AssociationField::new('couleur'),
        ];
    }
}

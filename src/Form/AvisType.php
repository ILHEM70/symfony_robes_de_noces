<?php
// src/Form/AvisType.php

namespace App\Form;

use App\Entity\Avis;
use App\Entity\Produits; // Assurez-vous d'importer l'entité Produits
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AvisType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Votre nom',
                'attr' => ['placeholder' => 'Entrez votre nom'],
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Votre commentaire',
                'attr' => ['placeholder' => 'Partagez votre avis ici...'],
            ])

            ->add('note', ChoiceType::class, [
                'label' => 'Votre note',
                'choices' => [
                    '1 étoile' => 1,
                    '2 étoiles' => 2,
                    '3 étoiles' => 3,
                    '4 étoiles' => 4,
                    '5 étoiles' => 5,
                ],
            ])
            ->add('produits', EntityType::class, [
                'class' => Produits::class,
                'label' => 'Produit concerné',
                'choice_label' => 'nom_du_produit', 
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer mon avis'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Avis::class, // L'entité associée au formulaire
        ]);
    }
}

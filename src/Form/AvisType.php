<?php
// src/Form/AvisType.php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class AvisType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Entrez votre nom',
                    'aria-label' => 'Votre nom complet',
                    'aria-required' => 'true',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le nom ne peut pas être vide.']),
                    new Length(['max' => 50, 'maxMessage' => 'Le nom ne doit pas dépasser 50 caractères.']),
                ],
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Votre commentaire',
                'attr' => [
                    'placeholder' => 'Partagez votre avis ici...',
                    'aria-label' => 'Votre commentaire sur le produit',
                    'aria-required' => 'true',
                    'rows' => 5, // Définit une taille de champ plus confortable
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le commentaire ne peut pas être vide.']),
                    new Length(['max' => 500, 'maxMessage' => 'Le commentaire ne doit pas dépasser 500 caractères.']),
                ],
            ])
            ->add('note', ChoiceType::class, [
                'label' => 'Votre note',
                'choices' => [
                    '⭐ 1 étoile' => 1,
                    '⭐⭐ 2 étoiles' => 2,
                    '⭐⭐⭐ 3 étoiles' => 3,
                    '⭐⭐⭐⭐ 4 étoiles' => 4,
                    '⭐⭐⭐⭐⭐ 5 étoiles' => 5,
                ],
                'expanded' => true, // Affiche les notes sous forme de boutons radio au lieu d’un menu déroulant
                'multiple' => false,
                'attr' => [
                    'aria-label' => 'Sélectionnez une note entre 1 et 5 étoiles',
                    'aria-required' => 'true',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner une note.']),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer mon avis',
                'attr' => [
                    'aria-label' => 'Soumettre votre avis',
                    'class' => 'btn-primary', // Ajoute une classe Bootstrap si nécessaire
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Avis::class, // L'entité associée au formulaire
        ]);
    }
}

<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adress', TextType::class, [
                'label' => 'Adresse',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez votre adresse',
                    'aria-label' => 'Votre adresse complète',
                    'aria-required' => 'true',
                ],
            ])
            ->add('code_postal', TextType::class, [
                'label' => 'Code Postal',
                'required' => true,
                'attr' => [
                    'aria-label' => 'Code postal',
                    'aria-required' => 'true',
                    'pattern' => '[0-9]{5}', // Pour forcer un format numérique à 5 chiffres
                    'title' => 'Veuillez entrer un code postal à 5 chiffres',
                ],
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'required' => true,
                'attr' => [
                    'aria-label' => 'Nom de la ville',
                    'aria-required' => 'true',
                ],
            ])
            ->add('pays', CountryType::class, [
                'label' => 'Pays',
                'required' => true,
                'placeholder' => 'Sélectionnez votre pays',
                'attr' => [
                    'aria-label' => 'Pays de résidence',
                    'aria-required' => 'true',
                ],
                'data' => 'FR', // Code ISO du pays par défaut
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'aria-label' => 'Enregistrer votre adresse',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}

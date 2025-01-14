<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mime\Address;
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
            ],
        ])
        ->add('code_postal', TextType::class, [
            'label' => 'Code Postal',
            'required' => true,
        ])
        ->add('ville', TextType::class, [
            'label' => 'Ville',
            'required' => true,
        ])
        ->add('pays', CountryType::class, [
            'label' => 'Pays',
            'required' => true,
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Enregistrer', // Texte sur le bouton
            'attr' => [
                'value' => 'Enregistrer',],
            ]);
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => Address::class,
        ]);
    }
}

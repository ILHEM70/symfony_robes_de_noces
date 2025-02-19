<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use DateTime;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero_de_carte', TextType::class, [
                'label' => 'Numéro de carte',
                'attr' => [
                    'placeholder' => '1234 5678 9012 3456',
                    'inputmode' => 'numeric',
                    'autocomplete' => 'cc-number',
                    'pattern' => '\d{16}',
                    'aria-label' => 'Numéro de carte bancaire sur 16 chiffres'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le numéro de carte est obligatoire.']),
                    new Length([
                        'min' => 16,
                        'max' => 16,
                        'exactMessage' => 'Le numéro de carte doit contenir 16 chiffres.'
                    ]),
                    new Regex([
                        'pattern' => '/^\d{16}$/',
                        'message' => 'Le numéro de carte doit contenir uniquement des chiffres.'
                    ])
                ]
            ])
            ->add('date_expiration', TextType::class, [
                'label' => 'Date d\'expiration (MM/YY)',
                'attr' => [
                    'placeholder' => 'MM/YY',
                    'inputmode' => 'numeric',
                    'autocomplete' => 'cc-exp',
                    'pattern' => '(0[1-9]|1[0-2])\/\d{2}',
                    'aria-label' => 'Date d\'expiration au format MM/YY'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La date d\'expiration est obligatoire.']),
                    new Regex([
                        'pattern' => '/^(0[1-9]|1[0-2])\/\d{2}$/',
                        'message' => 'Le format doit être MM/YY.'
                    ]),
                    new Callback([$this, 'validateExpirationDate'])
                ]
            ])
            ->add('cvv', PasswordType::class, [
                'label' => 'CVV',
                'attr' => [
                    'placeholder' => '123',
                    'inputmode' => 'numeric',
                    'autocomplete' => 'cc-csc',
                    'pattern' => '\d{3,4}',
                    'aria-label' => 'Code de sécurité de la carte (3 ou 4 chiffres)'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le CVV est obligatoire.']),
                    new Length([
                        'min' => 3,
                        'max' => 4,
                        'exactMessage' => 'Le CVV doit contenir 3 ou 4 chiffres.'
                    ]),
                    new Regex([
                        'pattern' => '/^\d{3,4}$/',
                        'message' => 'Le CVV doit contenir uniquement des chiffres.'
                    ])
                ]
            ])
            ->add('titulaire', TextType::class, [
                'label' => 'Nom du titulaire',
                'attr' => [
                    'placeholder' => 'Nom Prénom',
                    'autocomplete' => 'cc-name',
                    'aria-label' => 'Nom complet figurant sur la carte'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le nom du titulaire est obligatoire.']),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s\-]+$/',
                        'message' => 'Le nom ne doit contenir que des lettres et des espaces.'
                    ])
                ]
            ])
            ->add('payer', SubmitType::class, [
                'label' => 'Payer',
                'attr' => [
                    'aria-label' => 'Bouton pour effectuer le paiement'
                ]
            ]);
    }

    public function validateExpirationDate($value, ExecutionContextInterface $context)
    {
        $currentYear = (int) date('y');
        $currentMonth = (int) date('m');

        if (preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $value, $matches)) {
            $expMonth = (int) $matches[1];
            $expYear = (int) $matches[2];

            if ($expYear < $currentYear || ($expYear == $currentYear && $expMonth < $currentMonth)) {
                $context->buildViolation('La carte est expirée.')
                    ->addViolation();
            }
        }
    }
}

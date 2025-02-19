<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email',
                'attr' => [
                    'placeholder' => 'Votre email',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'L\'email est obligatoire.']),
                    new Email(['message' => 'Veuillez entrer une adresse email valide.'])
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Laisser vide si inchangé',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'max' => 50,
                        'minMessage' => 'Le mot de passe doit contenir au moins 8 caractères.',
                        'maxMessage' => 'Le mot de passe ne peut pas dépasser 50 caractères.'
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                        'message' => 'Le mot de passe doit contenir au moins une lettre, un chiffre et un caractère spécial.'
                    ])
                ]
            ])
            ->add('confirm_password', PasswordType::class, [
                'label' => 'Confirmer le mot de passe',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Confirmez votre mot de passe',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'La confirmation du mot de passe est obligatoire.']),
                    new Callback([$this, 'validatePasswordConfirmation'])
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Votre nom',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est obligatoire.']),
                    new Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\s-]+$/',
                        'message' => 'Le nom ne doit contenir que des lettres et des espaces.'
                    ])
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Votre prénom',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le prénom est obligatoire.']),
                    new Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\s-]+$/',
                        'message' => 'Le prénom ne doit contenir que des lettres et des espaces.'
                    ])
                ]
            ])
            ->add('Valider', SubmitType::class, [
                'label' => 'Mettre à jour',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    /**
     * Validation personnalisée pour vérifier que le mot de passe et sa confirmation correspondent.
     */
    public function validatePasswordConfirmation($value, ExecutionContextInterface $context)
    {
        // On récupère le password du formulaire
        $password = $context->getRoot()->get('password')->getData();

        // On compare les deux mots de passe
        if ($password && $value !== $password) {
            // Si les mots de passe ne correspondent pas, on ajoute une violation
            $context->buildViolation('Les mots de passe ne correspondent pas.')
                ->addViolation();
        }
    }
}

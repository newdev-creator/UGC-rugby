<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('last_name', TextType::class, [
                'label' => 'Nom *',
                'attr' => [
                    'placeholder' => 'Nom',
                ],
            ])
            ->add('first_name', TextType::class, [
                'label' => 'Prénom *',
                'attr' => [
                    'placeholder' => 'Prénom',
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'Email *',
                'attr' => [
                    'placeholder' => 'Email',
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone *',
                'attr' => [
                    'placeholder' => 'Téléphone',
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse *',
                'attr' => [
                    'placeholder' => 'Adresse',
                ]
            ])
            ->add('postal_code', TextType::class, [
                'label' => 'Code postal *',
                'attr' => [
                    'placeholder' => 'Code postal',
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville *',
                'attr' => [
                    'placeholder' => 'Ville',
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'attr' => [
                    'placeholder' => '',
                ],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez être d\'accord avec les termes d\'utilisation.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Mot de passe',
                    'pattern' => '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}',
                    ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe dois contenir 8 à 30 caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 30,
                    ]),
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'forms_register',
        ]);
    }
}

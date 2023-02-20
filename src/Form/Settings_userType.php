<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Settings_userType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom',
                    'class' => 'form-control'
                ],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Prénom',
                    'class' => 'form-control'
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Email',
                    'class' => 'form-control'
                ],
            ])
            ->add('phone', TextType::class, [
              'label' => 'Téléphone',
              'attr' => [
                'placeholder' => 'Email',
                'class' => 'form-control'
              ]
            ])
            ->add('address', TextType::class, [
              'label' => 'Adresse',
              'attr' => [
                'placeholder' => 'Adresse',
                'class' => 'form-control'
              ]
            ])
            ->add('postalCode', TextType::class, [
              'label' => 'Code postal',
              'attr' => [
                'placeholder' => 'Code postal',
                'class' => 'form-control'
              ]
            ])
            ->add('city', TextType::class, [
              'label' => 'Ville',
              'attr' => [
                'placeholder' => 'Ville',
                'class' => 'form-control'
              ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

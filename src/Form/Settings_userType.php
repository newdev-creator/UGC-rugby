<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
            ->add('userPictureFile', VichImageType::class, [
                'help' => 'Formats acceptés : jpg, jpeg, png. Taille maximale : 5Mo',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => false,
                'asset_helper' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/jpeg', 'image/png', 'image/jpg'
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un document image valide (jpg, jpeg, png)',
                    ])
                ],
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

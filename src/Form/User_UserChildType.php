<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\UserChild;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Form\Type\VichImageType;

class User_UserChildType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Prénom'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('birthday', DateType::class, [
                'label' => 'Date d\'anniversaire',
                'widget' => 'single_text',
                'required' => true,
                'input'  => 'datetime_immutable',
                'attr' => [
                    'placeholder' => 'Date d\'anniversaire',
                    'data-provider' => 'flatpickr',
                    'data-date-format' => 'd/m/Y',
                ],

            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégories',
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => [
                    'placeholder' => 'Catégories',
                    'data-choices' => true,
                ]
            ])
            ->add('childPictureFile', VichImageType::class, [
                'help' => 'Formats acceptés : jpg, jpeg, png. Taille maximale : 2Mo',
                'label' => 'Photo',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => false,
                'asset_helper' => false,
                'attr' => [
                    'placeholder' => 'Photo',
                    'data-choices' => true,
                ],
                
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
            'data_class' => UserChild::class,
        ]);
    }
}

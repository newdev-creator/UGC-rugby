<?php

namespace App\Form;

use App\Entity\Carpool;
use App\Entity\Event;
use App\Entity\User;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarpoolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [

                'label' => 'Date du rendez-vous',
                'widget' => 'single_text',
                'required' => true,
                'input'  => 'datetime_immutable',
                'format' => 'dd/MM/yyyy HH:mm',
                'html5' => false,
                'attr' => [
                    'placeholder' => 'Date du rendez-vous',
                    'data-provider' => 'flatpickr',
                    'data-date-format' => 'd/m/Y',
                    'data-enable-time' => 'false',
                ],

            ] )
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Adresse',
                ]
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => 'Code postal',
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Ville',
                ]
            ])
            ->add('comment', CKEditorType::class, [
                'label' => 'Commentaire',
                'attr' => [
                    'placeholder' => 'Commentaire',
                ]
            ])
            ->add('nbPlace', IntegerType::class, [
                'label' => 'Nombre de places',
                'attr' => [
                    'placeholder' => 'Nombre de places',
                ]
            ])
            ->add('users', EntityType::class, [
                'label' => 'Parent covoitureur',
                'class' => User::class,
                'choice_label' => 'getIdentity',
                'multiple' => false,
                'expanded' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Parent covoitureur',
                    'data-choices' => true,
                ],
            ])
            ->add('event', EntityType::class, [
                'label' => 'Evénement',
                'class' => Event::class,
                'choice_label' => 'title',
                'multiple' => false,
                'expanded' => false,
                'attr' => [
                    'placeholder' => 'Evénement',
                    'data-choices' => true,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carpool::class,
        ]);
    }
}

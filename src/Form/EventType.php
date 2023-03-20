<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Event;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Match' => Event::MATCH,
                    'Tournoi' => Event::TOURNAMENT,
                    'Entrainement' => Event::TRAINING,
                    'Autre' => Event::OTHER,
                ],
                'attr' => [
                    'placeholder' => 'Statut',
                    'data-choices' => true,
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Titre'
                ]
            ])
            ->add('date', DateType::class, [
                'label' => 'Date de l\'événement',
                'widget' => 'single_text',
                'required' => true,
                'input'  => 'datetime_immutable',
                'format' => 'dd/MM/yyyy HH:mm',
                'html5' => false,
                'attr' => [
                    'placeholder' => 'Date de l\'événement',
                    'data-provider' => 'flatpickr',
                    'data-date-format' => 'd/m/Y',
                    'data-enable-time' => 'false',
                ],

            ])
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
            ->add('description', CKEditorType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description',
                ]
            ])
            ->add('nbMinus', IntegerType::class, [
                'label' => 'Nombre d\'enfants minimum',
                'attr' => [
                    'placeholder' => 'Nombre d\'enfants minimum',
                ]
            ])
            ->add('categories', EntityType::class, [
                'label' => 'Catégories',
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'placeholder' => 'Catégories',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}

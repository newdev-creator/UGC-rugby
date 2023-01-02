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
                'choices' => [
                    'Match' => Event::MATCH,
                    'Tournoi' => Event::TOURNAMENT,
                    'Entrainement' => Event::TRAINING,
                    'Autre' => Event::OTHER,
                ],
                'attr' => [
                    'data-choices' => true,
                ],
            ])
            ->add('title', TextType::class)
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'input'  => 'datetime_immutable',
                'attr' => [
                    'data-provider' => 'flatpickr',
                    'data-date-format' => 'd/m/Y',
                    'data-enable-time' => 'false',
                ],
            ])
            ->add('address', TextType::class)
            ->add('postalCode', TextType::class)
            ->add('city', TextType::class)
            ->add('description', CKEditorType::class)
            ->add('nbMinus', IntegerType::class)
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
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

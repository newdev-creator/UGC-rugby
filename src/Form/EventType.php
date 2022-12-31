<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status')
            ->add('title')
            ->add('date')
            ->add('address')
            ->add('postalCode')
            ->add('city')
            ->add('description')
            ->add('nbMinus')
            ->add('nbRegistrant')
            ->add('addedAt')
            ->add('updatedAt')
            ->add('isActive')
            ->add('categories')
            ->add('child')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}

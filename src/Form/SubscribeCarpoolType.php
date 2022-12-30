<?php

namespace App\Form;

use App\Entity\Carpool;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscribeCarpoolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status')
            ->add('date')
            ->add('address')
            ->add('postalCode')
            ->add('city')
            ->add('comment')
            ->add('nbPlace')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carpool::class,
        ]);
    }
}

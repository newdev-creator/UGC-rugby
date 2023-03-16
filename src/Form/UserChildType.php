<?php

namespace App\Form;

use App\Entity\UserChild;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserChildType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // TODO: mettre en forme le formulaire
            ->add('firstName')
            ->add('lastName')
            ->add('birthday')
            ->add('updatedAt')
            ->add('isActive')
            ->add('addedAt')
            ->add('user')
            ->add('category')
            ->add('events')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserChild::class,
        ]);
    }
}

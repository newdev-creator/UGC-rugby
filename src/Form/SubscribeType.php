<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\UserChild;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscribeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // TODO:  modifier après avoir fait la page de login
            ->add('child', EntityType::class, [
                'class' => UserChild::class,
                'multiple' => true,
                'choice_label' => 'getIdentity',
                'label' => 'Enfant',
                'placeholder' => 'Choisissez un enfant',
                'attr' => [
                    'data-choices data-choices-limit' => '5',
                    'data-choices-removeItem' => 'true',
                ],
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

<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Secrétaire Baby' => 'ROLE_SECRETARY_BABY',
                    'Secrétaire U6' => 'ROLE_SECRETARY_U6',
                    'Secrétaire U8' => 'ROLE_SECRETARY_U8',
                    'Secrétaire U10' => 'ROLE_SECRETARY_U10',
                    'Secrétaire U12' => 'ROLE_SECRETARY_U12',
                    'Secrétaire U14' => 'ROLE_SECRETARY_U14',
                ],
                'multiple' => true,
                'expanded' => true,
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

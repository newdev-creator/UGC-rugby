<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\UserChild;
use App\Form\utils\CategoryChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserChildType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // use custom form type because doctrine want a string type for the category
        $builder
            ->add('category', CategoryChoiceType::class, [
                'label' => 'Catégorie',
                'attr' => [
                    'placeholder' => 'Catégorie',
                    'data-choices' => true,
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

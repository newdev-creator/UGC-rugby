<?php

namespace App\Form;

use App\Entity\Carpool;
use App\Entity\UserChild;
use App\Repository\UserChildRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class SubscribeCarpoolType extends AbstractType
{
    public function __construct(
        private readonly Security $security,
    ) {}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('child', EntityType::class, [
                'class' => UserChild::class,
                'choice_label' => 'getIdentity',
                'multiple' => true,
                'label' => 'Enfant(s)',
                'placeholder' => 'Choisissez un enfant',
                'required' => true,
                'query_builder' => function (UserChildRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.user = :user')
                        ->setParameter('user', $this->security->getUser())
                        ;
                },
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
            'data_class' => Carpool::class,
        ]);
    }
}

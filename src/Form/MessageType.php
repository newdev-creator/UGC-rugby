<?php

namespace App\Form;

use App\Entity\Message;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Titre'
                ]
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description',
                ]
            ])
            ->add('color', ChoiceType::class, [
                'label' => 'Niveau d\'importance',
                'choices' => [
                    'Important' => 'danger',
                    'Attention' => 'warning',
                    'Info' => 'info',
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => sprintf('text-%s', $value)];
                },
            ])

            ->add('startAt', DateType::class, [
                'label' => 'Date de l\'événement',
                'widget' => 'single_text',
                'required' => true,
                'input'  => 'datetime_immutable',
                'format' => 'dd/MM/yyyy',
                'html5' => false,
                'attr' => [
                    'placeholder' => 'Date de l\'événement',
                    'data-provider' => 'flatpickr',
                    'data-date-format' => 'd/m/Y',
                ],

            ])
            ->add('endAt', DateType::class, [
                'label' => 'Date de l\'événement',
                'widget' => 'single_text',
                'required' => true,
                'input'  => 'datetime_immutable',
                'format' => 'dd/MM/yyyy',
                'html5' => false,
                'attr' => [
                    'placeholder' => 'Date de l\'événement',
                    'data-provider' => 'flatpickr',
                    'data-date-format' => 'd/m/Y',
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}

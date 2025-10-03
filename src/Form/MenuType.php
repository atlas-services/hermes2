<?php

namespace App\Form;

use App\Entity\Menu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('active', CheckboxType::class, [
                'required' => false,
                'label' => 'form.label.active',
                'attr' => [
                    'class' => 'form-check-input ',
                ],
                'label_attr' => $options['label_attr']
                ])
            ->add('name', null ,  [
                'label' => 'form.label.name',
                'label_attr' => $options['label_attr']
                ])
            ->add('parent', EntityType::class, [
                    'required' => false,
                    'label' => 'form.label.parent',
                    'class' => Menu::class,
                    'choice_label' => 'name',
                    'placeholder' => 'form.label.parent_menu', // Option par défaut
                    'autocomplete' => true,
                    'label_attr' => $options['label_attr']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
            'label_attr' => [
                'class' => 'text-warning-emphasis ',
            ]
        ]);
    }
}

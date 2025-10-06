<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null ,  [
                'label' => 'form.label.name',
                'label_attr' => $options['label_attr']
            ])
            ->add('content', null ,  [
                'label' => 'form.label.content',
                'label_attr' => $options['label_attr']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'label_attr' => [
                'class' => 'text-warning-emphasis ',
            ]
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Post;

use App\Entity\Template;
use App\Form\AbstractNameBaseType;
use App\Repository\TemplateRepository;
use App\Service\MenuService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractNameBaseType
{
    public function __construct(private TemplateRepository $templateRepository, private MenuService $menuService)
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['label_name']= 'global.name';
        $options['tooltip']= 'Nom';
        $options['active'] = false;
        $options['name'] = false;
        parent::buildForm($builder, $options);
        $builder
        ->add('menu', EntityType::class,[
            'class' => Menu::class,
            'required' => true,
            'label' => 'form.label.menu',
            'autocomplete' => true,
            'choices' => $this->menuService->getMenusPage(),
            'attr'=> ['class' => 'custom-select custom-select-lg mb-3']
        ] )
            ->add('position', ChoiceType::class, [
                'required' => false,
                'attr' => [
                    'min' => 1,
                    'max' => 999,
                    'class' => 'custom-select custom-select-lg mb-3 ',
                    'label' => 'global.position',
                ],
                'choices' => range(0, 999),
            ])
            ->add('template', EntityType::class,
                [
                    'class'=> Template::class,
                    'required' => true,
                    'label' => 'form.label.post_template',
                    'autocomplete' => true,
                    'choices' => $this->templateRepository->getInitTemplates(),
                    'attr'=> ['class' => 'custom-select custom-select-lg mb-3']
            ])
            ->add('name', null, [
                'label' => 'form.label.name'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'form.label.content'
            ])


            ->add('templateWidth', ChoiceType::class, [
                    'choices' => $options['template_width'],
                    'required' => true,
                    'attr' => ['class' => 'custom-select custom-select-lg mb-3 '],
                    'label' => 'form.label.template_width',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'label_attr' => [
                'class' => 'text-warning-emphasis ',
            ],
            'template_width'=>  [
                '1/12' => '1',
                '2/12' => '2',
                '3/12' => '3',
                '4/12' => '4',
                '5/12' => '5',
                '6/12' => '6',
                '7/12' => '7',
                '8/12' => '8',
                '9/12' => '9',
                '10/12' => '10',
                '11/12' => '11',
                '12/12' => '12',
            ],
        ]);
    }
}

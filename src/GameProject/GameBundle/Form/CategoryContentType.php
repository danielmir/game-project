<?php
// src/GameProject/Form/CategoryContentType.php
namespace GameProject\GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'Name:',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('isActive', 'checkbox', [
                'label'     => 'Is active?',
                'required'  => false
            ])
            ->add('save', 'submit', [
                'label' => 'Create category',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    public function getName()
    {
        return 'categoryContent';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'GameProject\GameBundle\Entity\CategoryContent',
        ));
    }
}
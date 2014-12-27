<?php
// src/GameProject/Form/GameType.php
namespace GameProject\GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameType extends AbstractType
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
            ->add('description', 'textarea', [
                'label' => 'Description:',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => '4'
                ]
            ])
            ->add('link', 'text', [
                'label' => 'Game link:',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('categories', 'entity', [
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'property' => 'name',
                'class' => 'GameProjectGameBundle:Category',
                'label' => 'Game category',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('subdomain', 'entity', [
                'required' => false,
                'label_attr' => [
                    'class' => 'isNowActive'
                ],
                'placeholder' => 'Choose subdomain for this game...',
                'class' => 'GameProjectAdminBundle:Subdomain',
                'label' => 'Game subdomain',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // Change label name for save button, depending on action
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $game = $event->getData();
                $form = $event->getForm();

                if (null !== $game->getId()) {
                    $action = 'Update';
                } else {
                    $action = 'Create';
                }

                $form->add('save', 'submit', [
                    'label' => $action . ' game',
                    'attr' => [
                        'class' => 'btn btn-primary'
                    ]
                ]);
            });
    }

    public function getName()
    {
        return 'game';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'GameProject\GameBundle\Entity\Game',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention'       => 'task_item',
        ));
    }
}
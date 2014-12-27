<?php
// src/GameProject/Form/CategoryType.php
namespace GameProject\GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType
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
            ->add('subdomain', 'entity', [
                'required' => false,
                'placeholder' => 'Choose subdomain for this category...',
                'class' => 'GameProjectAdminBundle:Subdomain',
                'label' => 'Category subdomain',
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
                    'label' => $action . ' category',
                    'attr' => [
                        'class' => 'btn btn-primary'
                    ]
                ]);
            });
    }

    public function getName()
    {
        return 'category';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'GameProject\GameBundle\Entity\Category',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention'       => 'task_item',
        ));
    }
}
<?php
// src/GameProject/Form/GameContentType.php
namespace GameProject\GameBundle\Form;

use Doctrine\ORM\EntityRepository;
use GameProject\AdminBundle\Entity\Subdomain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameContentType extends AbstractType
{
    //Get subdomain from controller to know which language categories to show
    protected $subdomain;

    public function __construct (Subdomain $subdomain)
    {
        $this->subdomain = $subdomain;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $subdomain = $this->subdomain;
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
            ->add('isActive', 'checkbox', [
                'label'     => 'Is active?',
                'required'  => false
            ])
            // Get categories only associated by current subdomain
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($subdomain) {
                $form = $event->getForm();

                $form->add('category_contents', 'entity', [
                    'required' => false,
                    'multiple' => true,
                    'expanded' => true,
                    'property' => 'name',
                    'class' => 'GameProjectGameBundle:CategoryContent',
                    'query_builder' => function(EntityRepository $er) use ($subdomain) {
                        return $er->createQueryBuilder('c')
                            ->where('c.subdomain = ' . $subdomain->getId());
                    },
                    'label' => 'Game category',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]);
            })
            ->add('save', 'submit', [
                'position' => 'last',
                'label' => 'Create game',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    public function getName()
    {
        return 'gameContent';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'GameProject\GameBundle\Entity\GameContent',
        ));
    }
}
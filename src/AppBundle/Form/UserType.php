<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class UserType extends AbstractType
//class UserType extends BaseType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('username')
            ->add('enabled')
            ->add('email')
            ->add('roles')
            ->add('password')
//            ->add('roles', 'choice', array(
//                'label' => 'I am:',
//                'mapped' => true,
//                'expanded' => true,
//                'multiple' => true,
//                'choices' => array(
//                    'ROLE_USER' => 'USER',
//                    'ROLE_ADMIN' => 'ADMIN',
//                )
//            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }
}

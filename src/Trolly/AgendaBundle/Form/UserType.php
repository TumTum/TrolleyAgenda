<?php

namespace Trolly\AgendaBundle\Form;

use FOS\UserBundle\Form\Type\ChangePasswordFormType;
use FOS\UserBundle\Form\Type\GroupFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstlastname', TextType::class, array('label' =>'label.name'))
            ->add('username', TextType::class, array('label' =>'label.username'))
            ->add('email', EmailType::class, array('label' =>'label.email'))
            ->add('plainPassword', PasswordType::class, array('label' => 'label.password', "required" => false))
//            ->add('roles', ChoiceType::class, array(
//                'label' =>'label.role.label',
//                'choices' => array(
//                    'label.role.admin' => 'ROLE_ADMIN',
//                    'label.role.user' => 'ROLE_USER',
//                ),
//                'choices_as_values' => true,
//            ))
            ->add('street', TextType::class, array('label' =>'label.street'))
            ->add('plz', TextType::class, array('label' =>'label.plz'))
            ->add('city', TextType::class, array('label' =>'label.city'))
            ->add('phone', TextType::class, array('label' =>'label.phone', "required" => false))
            ->add('mobile', TextType::class, array('label' =>'label.mobile', "required" => false))
            ->add('mobile2', TextType::class, array('label' =>'label.mobile', "required" => false))
        ;

    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Trolly\AgendaBundle\Entity\User'
        ));
    }
}

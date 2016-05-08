<?php

namespace Trolley\AgendaBundle\Form;

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
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstlastname', TextType::class, ['label' => 'label.name'])
            ->add('username', TextType::class, ['label' => 'label.username'])
            ->add('email', EmailType::class, ['label' => 'label.email'])
            ->add('plainPassword', PasswordType::class, ['label' => 'label.password', "required" => false])
            ->add('adminRole', ChoiceType::class, [
                'label' =>'label.role.label',
                'choices' => [
                    'label.role.user' => 'no',
                    'label.role.admin' => 'yes'
                ],
            ])
            ->add('street', TextType::class, ['label' => 'label.street'])
            ->add('plz', TextType::class, ['label' => 'label.plz'])
            ->add('city', TextType::class, ['label' => 'label.city'])
            ->add('phone', TextType::class, ['label' => 'label.phone', "required" => false])
            ->add('mobile', TextType::class, ['label' => 'label.mobile', "required" => false])
            ->add('mobile2', TextType::class, ['label' => 'label.mobile', "required" => false]);
    }




    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'data_class' => 'Trolley\AgendaBundle\Entity\User'
        ]);
    }
}

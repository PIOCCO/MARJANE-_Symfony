<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Build the form with fields for the User entity
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Register',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Configure the form to use the User entity
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

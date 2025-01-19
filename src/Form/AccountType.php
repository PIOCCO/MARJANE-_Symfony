<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Account Name field
            ->add('accountName', TextType::class, [  // Use 'AccountName' to match the entity property
                'label' => 'Account Name',
                'attr' => ['placeholder' => 'Enter account name'],
                'required' => true,
            ])
            // Is Closed checkbox
            ->add('isClosed', CheckboxType::class, [
                'label' => 'Is Closed',
                'required' => false, // Make it optional
                'value' => false, // Default value if unchecked
            ])
            // Is VIP checkbox
            ->add('isVip', CheckboxType::class, [
                'label' => 'Is VIP',
                'required' => false, // Make it optional
                'value' => false, // Default value if unchecked
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class, // Ensure the form is linked to the Account entity
        ]);
    }
}

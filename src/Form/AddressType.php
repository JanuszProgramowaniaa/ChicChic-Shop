<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('firstName', TextType::class, [
            'label' => 'First Name',
            'required' => false,
            'constraints' => [new NotBlank()],
        ])
        ->add('lastName', TextType::class, [
            'required' => false,
            'label' => 'Last Name',
            'constraints' => [new NotBlank()],
        ])
        ->add('company', TextType::class, ['label' => 'Company', 'required' => false])
        ->add('phone', TextType::class, [
            'required' => false,
            'label' => 'Phone',
            'constraints' => [new NotBlank(),new Length(['max' => 12])],
            
        ])
        ->add('address', TextType::class, [
            'required' => false,
            'label' => 'Address',
            'constraints' => [new NotBlank()],
        ])
        ->add('zip', TextType::class, [
            'required' => false,
            'label' => 'ZIP Code',
            'constraints' => [new NotBlank()],
        ])
        ->add('submit', SubmitType::class, ['label' => 'Submit']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
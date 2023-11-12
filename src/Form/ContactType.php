<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Email cannot be blank']),
                ],
            ])
            ->add('message', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Message cannot be blank']),
                ],
            ])
        ;
    }

    
}
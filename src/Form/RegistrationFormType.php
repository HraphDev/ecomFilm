<?php

// src/Form/RegistrationFormType.php

namespace App\Form;

use App\Entity\User; // Your User entity (this should already exist)
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'Email Address',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('username', TextType::class, [
            'label' => 'Username',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'first_options' => [
                'label' => 'Password',
                'attr' => ['class' => 'form-control'],
            ],
            'second_options' => [
                'label' => 'Confirm Password',
                'attr' => ['class' => 'form-control'],
            ],
        ])
        ->add('firstName', TextType::class, [
            'label' => 'First Name',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('lastName', TextType::class, [
            'label' => 'Last Name',
            'attr' => ['class' => 'form-control'],
 
        ])
        ->add('profilePicture', FileType::class, [
            'label' => 'Profile Picture',
            'mapped' => false, // We handle this manually in the controller
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => ['image/png', 'image/jpeg', 'image/jpg'],
                    'mimeTypesMessage' => 'Please upload a valid image (png, jpg, jpeg).',
                ])
            ],
            'attr' => ['class' => 'form-control'],
        ]);
}
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class, // Bind the form to the User entity
        ]);
    }
}

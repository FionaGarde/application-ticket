<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', null, [
                'required' => true
            ])
            ->add('lastname', null, [
                'required' => true
            ])
            ->add('mail', null, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^.*@my-digital-school.org$/',
                        'message' => 'Adresse mail MyDigitalSchool uniquement',
                    ])
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new Regex([
                        // check for at least 1 digit, 1 lower and 1 upper character, 1 special character, 8 characters minimum and 4096 characters maximum
                        'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,4096}$/',
                        'message' => 'Le mot de passe doit contenir au moins 1 chiffre, 1 minuscule, 1 majuscule, 1 caractère spécial et 8 caractères'
                    ])
                ],
            ])
            ->add('room', null, [
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

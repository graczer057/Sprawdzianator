<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class,  [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Adres email* '
            ])
            ->add('name', TextType::class,  [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Imię* '
            ])
            ->add('surname', TextType::class,  [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nazwisko* '
            ])
            ->add('1Password', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'label' => 'Hasło* '
            ])
            ->add('2Password', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
                'label' => 'Powtórz hasło* '
            ])
            ->add('roles', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Twoja rola w szkole* ',
                'choices' => [
                    'Nauczyciel' => 'ROLE_TEACHER',
                    'Uczeń' => 'ROLE_STUDENT'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Akceptacja regulaminu jest obowiązkowa do założenia konta w naszym serwisie.',
                    ]),
                ],
                'label' => 'Akceptacja regulaminu (link wkrótce)* ',
            ])
            ->add('btn_submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Zarejestruj się'
            ]);
    }
}

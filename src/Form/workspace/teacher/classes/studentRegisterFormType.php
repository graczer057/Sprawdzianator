<?php

namespace App\Form\workspace\teacher\classes;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class studentRegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class,  [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Adres email: '
            ])
            ->add('name', TextType::class,  [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Imię: '
            ])
            ->add('surname', TextType::class,  [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nazwisko: '
            ])
            ->add('1Password', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę podać hasło',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Twoje hasło powinno mieć min. {{ limit }} znaków',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'label' => 'Hasło: '
            ])
            ->add('2Password', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Proszę powtórzyć hasło',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Twoje hasło powinno mieć min. {{ limit }} znaków',
                        'max' => 4096,
                    ]),
                ],
                'label' => 'Powtórz hasło: '
            ])
            ->add('btn_submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Zarejestruj się'
            ]);
    }
}
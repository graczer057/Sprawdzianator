<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ExpireFormType extends AbstractType
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
            ->add('btn_submit', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary'
            ],
            'label' => 'Wyślij ponownie'
        ]);
    }
}
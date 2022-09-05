<?php

namespace App\Form\workspace\student;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class studentChooseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('grade', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => '2',
                    'max' => '6'
                ],
                'required' => true,
                'label' => 'Jakiej oceny oczekujesz od tego sprawdzianu: '
            ])
            ->add('btn_submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Zacznij rozwiązywać'
            ]);
    }
}
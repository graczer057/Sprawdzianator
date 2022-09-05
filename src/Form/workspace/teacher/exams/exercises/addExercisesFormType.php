<?php

namespace App\Form\workspace\teacher\exams\exercises;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class addExercisesFormType extends AbstractType
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
                'label' => 'Jakiej oceny mogą spodziewać się uczniowie za wykonanie tych zadań: '
            ])
            ->add('exercises', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Numery zadań do wykonania. Jeśli zadania nie są zadaniami z podręcznika to proszę tutaj zapisać treść pierwszego zadania, a kolejne do tej grupy/oceny proszę kliknąć w poniższy link: '
            ])
            ->add('group', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => '1',
                    'max' => '2'
                ],
                'label' => 'Numer grupy zadaniowej: '
            ])
            ->add('btn_submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Dodaj zadania do sprawdzianu'
            ]);
    }
}
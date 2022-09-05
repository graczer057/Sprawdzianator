<?php

namespace App\Form\workspace\teacher\exams;

use App\Entity\Classes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class editExamFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Tytuł: '
            ])
            ->add('subject', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Przedmiot szkolny: '
            ])
            ->add('activeDate', DateTimeType::class, [
                'attr' => [
                    'class' => 'js-datepicker'
                ],
                'widget' => 'single_text',
                'html5' => 'false',
                'placeholder' => [
                    'year' => 'Rok', 'month' => 'Miesiąc', 'day' => 'Dzień', 'hour' => 'Godzina', 'minute' => 'Minuta',
                ],
                'label' => 'Termin zakończenia sprawdzianu: '
            ])
            ->add('class', EntityType::class, [
                'class' => Classes::class,
                'choice_label' => 'name',
                'label' => 'Klasa'
            ])
            ->add('btn_submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Edytuj sprawdzian'
            ]);
    }
}
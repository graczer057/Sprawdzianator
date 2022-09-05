<?php

namespace App\Form\workspace\student;

use App\Entity\StudentPictures;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\AllValidator;
use Symfony\Component\Validator\Constraints\File;

class studentSolvingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('picture', FileType::class, [
                'required' => true,
                'multiple' => true,
                'attr' => [
                    'class' => 'form-control',
                    'multiple' => 'multiple'
                ],
                'label' => 'Zdjęcie/a rozwiązanych zadań w zeszycie',
            ])
            ->add('btn_submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Prześlij zdjęcia'
            ])
        ;
    }
}
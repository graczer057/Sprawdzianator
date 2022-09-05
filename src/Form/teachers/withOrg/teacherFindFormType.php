<?php

namespace App\Form\teachers\withOrg;

use App\Entity\Organisations;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class teacherFindFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('org', EntityType::class, [
                'class' => Organisations::class,
                'choice_label' => 'name',
            ])
            ->add('btn_submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Dołącz do organizacji'
            ]);
    }
}
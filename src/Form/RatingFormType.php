<?php

namespace App\Form;

use App\Entity\CarReview;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', ChoiceType::class, [
                'choices' => [
                    '-1' => -1,
                    '0' => 0,
                    '1' => 1
                ],
                'data' => '0',
                'expanded' => true
            ])
            ->add('timestamp', null, [
                'attr' => [
                    'style' => 'display: none;'
                ],
                'label' => false
            ])
            ->add('user', null, [
                'attr' => [
                    'style' => 'display: none;'
                ],
                'label' => false
            ])
            ->add('car', null, [
                'attr' => [
                    'style' => 'display: none;'
                ],
                'label' => false
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CarReview::class,
        ]);
    }
}

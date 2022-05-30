<?php

namespace App\Form;

use App\Entity\Manufacturer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('manufacturer', EntityType::class, [
                'class' => Manufacturer::class,
                'placeholder' => '-- Choose a manufacturer --',
                'required' => false
            ])
            ->add('engine_min_power', IntegerType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 1000
                ],
                'label' => 'Engine power minimum',
                'required' => false
            ])
            ->add('rating', IntegerType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 100
                ],
                'label' => 'Minimal rating',
                'required' => false
            ])
            ->add('order_by_field', ChoiceType::class, [
                'label' => 'Field to order by',
                'choices' => [
                    'Engine power' => 'power',
                    'Engine torque' => 'torque',
                    'Car rating' => 'rating'
                ],
                'data' => 'power',
                'expanded' => true
            ])
            ->add('order_by_direction', ChoiceType::class, [
                'label' => 'Order direction',
                'choices' => [
                    'Ascending' => 'ASC',
                    'Descending' => 'DESC'
                ],
                'data' => 'ASC',
                'expanded' => true
            ])
            ->add('fuel', ChoiceType::class, [
                'choices' => [
                    'Petrol' => 'petrol',
                    'Diesel' => 'diesel',
                    'LPG' => 'lpg',
                    'CNG' => 'cng'
                ],
                'placeholder' => '-- Choose fuel --',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success p-3'
                ]
            ])
       ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'get'
        ]);
    }
}
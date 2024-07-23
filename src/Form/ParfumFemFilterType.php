<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParfumFemFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('marque', ChoiceType::class, [
                'choices' => [
                    'Toutes' => '',
                    'Chanel' => 'Chanel',
                    'Dior' => 'Dior',
                    'Lancôme' => 'Lancôme',
                    'Yves Saint Laurent' => 'Yves Saint Laurent',
                    'Dolce & Gabbana' => 'Dolce & Gabbana',
                ],
                'required' => false,
                'label' => 'Marque',


            ])
            ->add('concentration', ChoiceType::class, [
                'choices' => [

                    'Toutes' => '',
                    'Eau de Toilette' => 'Eau de Toilette',
                    'Eau de Parfum' => 'Eau de Parfum',
                    'Parfum' => 'parfum',
                ],
                'required' => false,
                'label' => 'Concentration',

            ])
            ->add('prix', ChoiceType::class, [
                'choices' => [
                    'Tous' => '',
                    '< 30 €' => '0-30',
                    '30 à 50 €' => '30-50',
                    '50 à 100 €' => '50-100',
                    '100 à 150 €' => '100-150',
                    '150 à 250 €' => '150-250',
                    '250 à 500 €' => '250-500',
                ],
                'required' => false,
                'label' => 'Prix',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

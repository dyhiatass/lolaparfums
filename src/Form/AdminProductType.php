<?php
namespace App\Form;

use App\Entity\Parfums;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AdminProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('marque')
            ->add('description')
            ->add('image', FileType::class, [
                'label' => 'Image (JPEG, PNG file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPEG or PNG)',
                    ])
                ],
            ])
    
            ->add('genre')
            ->add('concentration')
            ->add('isCoffret', CheckboxType::class, [
                'label' => 'Is it a Coffret?',
                'mapped' => false,
                'required' => false,
            ])
            ->add('tendance', CheckboxType::class, [
                'label' => 'Tendance',
                'required' => false,
            ])
            ->add('coupDeCoeur', CheckboxType::class, [
                'label' => 'Coup de Coeur',
                'required' => false,
            ])
            ->add('meilleursVente', CheckboxType::class, [
                'label' => 'Meilleure Vente',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Parfums::class,
        ]);
    }
}

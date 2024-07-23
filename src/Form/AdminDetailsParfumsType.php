<?PHP

namespace App\Form;

use App\Entity\Parfums;
use App\Entity\DetailsParfum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints as Assert;

class AdminDetailsParfumsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
$detailParfum = new DetailsParfum();
        $builder
            ->add('parfums', EntityType::class, [
                'class' => Parfums::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un produit',
            ])
            ->add('prix', NumberType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le prix ne peut pas être vide.'
                    ]),
                    new Assert\Positive([
                        'message' => 'Le prix doit être un nombre positif.'
                    ]),
                ],
            ])
            
            ->add('quantite', NumberType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La quantité ne peut pas être vide.']),
                    new Assert\Regex([
                        'pattern' => "/^[0-9]+$/",
                        'message' => 'La quantité doit être un nombre positif.']), ],])


            ->add('taille', ChoiceType::class, [
                'choices'=>$detailParfum->getTailleChoices(),
                'placeholder' => 'Choisir une taille',
                'required' => false,
                
            ])

            ->add('promotion', CheckboxType::class, [
                'label' => 'Promotion',
                'required' => false,
            ])
            ->add('pourcentagePromotion', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex(['pattern' => "/^\d+(\.\d+)?$/", 'message' => 'Le pourcentage doit être un nombre positif ou zéro.']),
                ],
            ]);
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DetailsParfum::class,
        ]);
    }
}

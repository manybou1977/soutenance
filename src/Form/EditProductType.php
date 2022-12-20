<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EditProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label'=>'Nom du produit',
                'required'=>false,
                'attr'=>[
        'placeholder'=>'Saisissez le nom du produit'
                ]
             ])

            ->add('categorie', EntityType::class,[
                'label'=>'Catégorie',
                'required'=>false,
                'class'=>Categorie::class,
                'choice_label'=>'nom',
                'placeholder'=>'Sélectionnez une catégorie'
            ])

            ->add('prix', NumberType::class,[
                'label'=>'Prix du produit',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez le prix du produit'
                ]

            ])

            ->add('menu', CheckboxType::class,[
                'label'=>'Possible avec menu?',
                'required'=>false,
                'value'=>1


            ])

            ->add('description', TextareaType::class,[
                'label'=>'Description du produit',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez la description du produit'
                ]

            ])

            ->add('editPhoto', FileType::class,[
                'label'=>'Photo',
                'required'=>false,
                'constraints'=>[
                    new File([
                        'mimeTypes'=>[
                            "image/png",
                            "image/jpg",
                            "image/jpeg",
                            "image/gif",
                            "image/jfif",
                            "image/webp"

                        ],
                        'mimeTypesMessage'=>'Format non géré'
                    ])

                ]

            ])

            ->add('enregistrer', SubmitType::class)
        ;

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

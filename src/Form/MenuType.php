<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Menu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
            'label'=>'Nom du produit',
            'required'=>false,
             'attr'=>[
                'placeholder'=>'Saisissez le nom du produit'
             ]
        ])

            ->add('prix', NumberType::class,[
            'label'=>'Prix du produit',
            'required'=>false,
            'attr'=>[
                'placeholder'=>'Saisissez le prix du produit'
            ]

        ])



            ->add('description', TextareaType::class,[
                'label'=>'Description du produit',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Saisissez la description du produit'
                ]

            ])

        ->add('enregistrer', SubmitType::class)
    ;


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}

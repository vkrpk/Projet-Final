<?php

namespace App\Form;

use App\Entity\Jeu;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class JeuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Titre de l\'annonce',
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix',
                'divisor' => 100,
            ])
            ->add('date', DateType::class, [
                'label' => false,
                'attr' => [
                    'style' => 'display:none'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/jpg',
                        ]
                    ]),
                ]
            ])
            ->add('lieu', TextType::class, [
                'label' => 'Ville'
            ])
            ->add(
                'categorie',
                EntityType::class,
                [
                    'label' => 'Catégorie',
                    'attr' => [],
                    'placeholder' => '-- Choisir une catégorie --',
                    'class' => Categorie::class,
                    'choice_label' => function (Categorie $categorie) {
                        return strtoupper($categorie->getNom());
                    }
                ]
            )
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter une annonce'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Jeu::class,
        ]);
    }
}

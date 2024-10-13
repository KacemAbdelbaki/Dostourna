<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Investments;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Validator\Constraints  as Assert;
class InvestmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom ne peut pas être vide.']),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/', // Permet seulement les lettres et les espaces
                        'message' => 'Le nom doit contenir uniquement des lettres.',
                        
                    ]),
                ],
            ])
            ->add('price', MoneyType::class, [
                'currency' => 'BTC',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prix ne peut pas être vide.']),
                    new Assert\Positive(['message' => 'Le prix doit être un nombre positif.']),
                ],
            ])
            ->add('type', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le type ne peut pas être vide.']),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/', // Permet seulement les lettres et les espaces
                        'message' => 'Le type doit contenir uniquement des lettres.',
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La description ne peut pas être vide.']),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z0-9\s]+$/', // Permet seulement les lettres, les chiffres et les espaces
                        'message' => 'La description doit contenir uniquement des lettres et des chiffres.',
                    ]),
                ],
            ])
            ->add('latitude', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Longitude',
                    'style' => 'width: 100%; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);',
                    'value'=> '33.8869',
                    'readonly'=>'readonly'
                ],
            ])
            ->add('longitude', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Longitude',
                    'style' => 'width: 100%; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);',
                    'value'=> '9.5375',
                    'readonly'=>'readonly'
                ],
            ])
            ->add('categorie', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image du Projet',
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Investments::class,
        ]);
    }
}

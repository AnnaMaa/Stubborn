<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('price', MoneyType::class, ['label' => 'Prix', 'currency' => 'EUR'])
            ->add('image', TextType::class, ['label' => 'Image (chemin)', 'required' => false])

            
            ->add('stockXs', IntegerType::class, ['label' => 'Stock XS', 'required' => false])
            ->add('stockS', IntegerType::class, ['label' => 'Stock S', 'required' => false])
            ->add('stockM', IntegerType::class, ['label' => 'Stock M', 'required' => false])
            ->add('stockL', IntegerType::class, ['label' => 'Stock L', 'required' => false])
            ->add('stockXl', IntegerType::class, ['label' => 'Stock XL', 'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

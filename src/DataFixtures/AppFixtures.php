<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            ['Blackbelt', 29.90, 'uploads/products/images/Blackbelt.jpeg'],
            ['Bluebelt', 29.90, 'uploads/products/images/Bluebelt.jpeg'],
            ['Street', 34.50, 'uploads/products/images/Street.jpeg'],
            ['Pokeball', 45.00, 'uploads/products/images/Pokeball.jpeg'],
            ['PinkLady', 29.90, 'uploads/products/images/PinkLady.jpeg'],
            ['Snow', 32.00, 'uploads/products/images/Snow.jpeg'],
            ['Greyback', 28.50, 'uploads/products/images/Greyback.jpeg'],
            ['BlueCloud', 45.00, 'uploads/products/images/BlueCloud.jpeg'],
            ['BornInUsa', 59.90, 'uploads/products/images/BornInUsa.jpeg'],
            ['GreenSchool', 42.20, 'uploads/products/images/GreenSchool.jpeg'],
        ];

        foreach ($products as [$name, $price, $image]) {
            $product = (new Product())
                ->setName($name)
                ->setPrice($price)
                ->setImage($image)
                ->setStockXs(2)
                ->setStockS(2)
                ->setStockM(2)
                ->setStockL(2)
                ->setStockXl(2);

            $manager->persist($product);
        }

        $manager->flush();
    }
}

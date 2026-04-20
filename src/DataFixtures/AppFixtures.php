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
            ['Blackbelt', 29.90, 'uploads/products/images/Blackbelt.jpg'],
            ['Bluebelt', 29.90, 'uploads/products/images/Bluebelt.jpg'],
            ['Street', 34.50, 'uploads/products/images/Street.jpg'],
            ['Pokeball', 45.00, 'uploads/products/images/Pokeball.jpg'],
            ['PinkLady', 29.90, 'uploads/products/images/PinkLady.jpg'],
            ['Snow', 32.00, 'uploads/products/images/Snow.jpg'],
            ['Greyback', 28.50, 'uploads/products/images/Greyback.jpg'],
            ['BlueCloud', 45.00, 'uploads/products/images/BlueCloud.jpg'],
            ['BornInUsa', 59.90, 'uploads/products/images/BornInUsa.jpg'],
            ['GreenSchool', 42.20, 'uploads/products/images/GreenSchool.jpg'],
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

<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

public function findByPriceRange($min, $max): array
{
    return $this->createQueryBuilder('p')
        ->andWhere('p.price >= :min')
        ->andWhere('p.price <= :max')
        ->setParameter('min', $min)
        ->setParameter('max', $max)
        ->orderBy('p.price', 'ASC')
        ->getQuery()
        ->getResult();
}


}

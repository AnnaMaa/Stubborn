<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        // Produits mis en avant sur la page d'accueil
        $featuredProducts = $productRepository->createQueryBuilder('p')
            ->where('p.name IN (:names)')
            ->setParameter('names', [
                'Blackbelt',
                'Pokeball',
                'BornInUSA',
            ])
            ->getQuery()
            ->getResult();

        return $this->render('home/index.html.twig', [
            'featuredProducts' => $featuredProducts,
        ]);
    }
}
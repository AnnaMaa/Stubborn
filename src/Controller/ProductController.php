<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; // ✅ IMPORTANT
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
  #[Route('/products', name: 'app_products')]
public function index(Request $request, ProductRepository $productRepository): Response
{
    $range = $request->query->get('range'); 

    if ($range) {
        [$min, $max] = array_map('floatval', explode('-', $range));
        $products = $productRepository->findByPriceRange($min, $max);
    } else {
        $products = $productRepository->findAll();
    }

    return $this->render('product/index.html.twig', [
        'products' => $products,
    ]);
}


    #[Route('/products/{id}', name: 'app_product_show', requirements: ['id' => '\d+'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}


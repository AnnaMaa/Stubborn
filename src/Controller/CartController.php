<?php

namespace App\Controller;

use App\Service\CartService;
use App\Service\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_index', methods: ['GET'])]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $cartService->getItems(),
            'total' => $cartService->getTotal(),
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function add(int $id, CartService $cartService, Request $request): Response
    {
        $size = $request->request->get('size', 'M');
        $cartService->add($id, $size);

        $this->addFlash('success', 'Produit ajouté au panier.');
        return $this->redirectToRoute('cart_index');
    }

    
    #[Route('/cart/remove/{id}/{size}', name: 'cart_remove', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function remove(int $id, string $size, CartService $cartService): Response
    {
        $cartService->remove($id, $size);

        $this->addFlash('success', 'Produit retiré du panier.');
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/clear', name: 'cart_clear', methods: ['GET'])]
    public function clear(CartService $cartService): Response
    {
        $cartService->clear();

        $this->addFlash('success', 'Panier vidé.');
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/checkout', name: 'cart_checkout', methods: ['GET'])]
    public function checkout(CartService $cartService, StripeService $stripeService): Response
    {
        $items = $cartService->getItems();

        if (empty($items)) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('cart_index');
        }

        $checkoutUrl = $stripeService->createCheckoutSession($items);
        return $this->redirect($checkoutUrl);
    }

    #[Route('/cart/success', name: 'cart_success', methods: ['GET'])]
    public function success(CartService $cartService): Response
    {
        $cartService->clear();
        return $this->render('cart/success.html.twig');
    }
}

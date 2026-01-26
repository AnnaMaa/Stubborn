<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private const CART_KEY = 'cart';

    private ?SessionInterface $session = null;

    public function __construct(
        RequestStack $requestStack,
        private ProductRepository $productRepository
    ) {
        $request = $requestStack->getCurrentRequest();
        if ($request) {
            $this->session = $request->getSession();
        }
    }

    private function makeKey(int $productId, string $size): string
    {
        $size = strtoupper(trim($size));
        if (!in_array($size, ['XS','S','M','L','XL'], true)) {
            $size = 'M';
        }
        return $productId . '|' . $size;
    }

    public function add(int $productId, string $size = 'M'): void
    {
        if (!$this->session) {
            return;
        }

        $cart = $this->session->get(self::CART_KEY, []);
        $key = $this->makeKey($productId, $size);

        $cart[$key] = ($cart[$key] ?? 0) + 1;

        $this->session->set(self::CART_KEY, $cart);
    }

    public function remove(int $productId, string $size = 'M'): void
    {
        if (!$this->session) {
            return;
        }

        $cart = $this->session->get(self::CART_KEY, []);
        $key = $this->makeKey($productId, $size);

        unset($cart[$key]);

        $this->session->set(self::CART_KEY, $cart);
    }

    public function clear(): void
    {
        if (!$this->session) {
            return;
        }
        $this->session->remove(self::CART_KEY);
    }

    public function getItems(): array
    {
        if (!$this->session) {
            return [];
        }

        $cart = $this->session->get(self::CART_KEY, []);
        $items = [];

        foreach ($cart as $key => $quantity) {
            
            [$productId, $size] = explode('|', (string) $key) + [null, 'M'];

            $productId = (int) $productId;
            $product = $this->productRepository->find($productId);

            if (!$product) {
                continue;
            }

            $items[] = [
                'product'  => $product,
                'size'     => $size,
                'quantity' => (int) $quantity,
                'subtotal' => $product->getPrice() * (int) $quantity,
            ];
        }

        return $items;
    }

    public function getTotal(): float
    {
        $total = 0.0;
        foreach ($this->getItems() as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }
}

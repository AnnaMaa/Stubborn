<?php

namespace App\Service;

use Stripe\StripeClient;

class StripeService
{
    private StripeClient $client;

      public function __construct(string $stripeSecretKey)
    {

        $stripeSecretKey = trim($stripeSecretKey);
        
        if ($stripeSecretKey === '' || str_contains($stripeSecretKey, 'sk_test_')) {
        
    }

        $this->client = new StripeClient($stripeSecretKey);
       
    }
    public function createCheckoutSession(array $cartItems): string
    {
        $lineItems = [];

        foreach ($cartItems as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $product->getName(),
                    ],
                    
                    'unit_amount' => (int) ($product->getPrice() * 100),
                ],
                'quantity' => $quantity,
            ];
        }

        $session = $this->client->checkout->sessions->create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'success_url' => 'http://127.0.0.1:8000/cart/success',
            'cancel_url'  => 'http://127.0.0.1:8000/cart',
        ]);

        return $session->url;
    }
}

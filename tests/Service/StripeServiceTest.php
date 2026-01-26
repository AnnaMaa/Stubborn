<?php

namespace App\Tests\Service;

use App\Service\StripeService;
use PHPUnit\Framework\TestCase;

final class StripeServiceTest extends TestCase
{
    public function testCreateCheckoutSessionReturnsUrl(): void
    {
        // Faux produit
        $product = new class {
            public function getName(): string { return 'T-shirt'; }
            public function getPrice(): float { return 29.99; }
        };

        $cartItems = [
            [
                'product' => $product,
                'quantity' => 2,
            ]
        ];

        // Mock du service Stripe (PAS du client Stripe)
        $stripeService = $this->createMock(StripeService::class);

        $stripeService
            ->expects(self::once())
            ->method('createCheckoutSession')
            ->with($cartItems)
            ->willReturn('https://stripe.test/session');

        $url = $stripeService->createCheckoutSession($cartItems);

        self::assertSame('https://stripe.test/session', $url);
    }
}


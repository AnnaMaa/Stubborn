<?php

namespace App\Tests\Controller;

use App\Service\CartService;
use App\Service\StripeService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CartControllerTest extends WebTestCase
{
    public function testCheckoutRedirectsToStripeWhenCartNotEmpty(): void
    {
        $client = static::createClient();

        
        $product = new class {
            public function getName(): string { return 'T-shirt'; }
            public function getPrice(): float { return 19.99; }
        };

        $fakeCartItems = [
            [
                'product'  => $product,
                'size'     => 'M',
                'quantity' => 2,
                'subtotal' => 39.98,
            ],
        ];

        /** @var CartService&MockObject $cartService */
        $cartService = $this->createMock(CartService::class);
        $cartService->method('getItems')->willReturn($fakeCartItems);
        $cartService->method('getTotal')->willReturn(39.98);

        /** @var StripeService&MockObject $stripeService */
        $stripeService = $this->createMock(StripeService::class);
        $stripeService
            ->expects(self::once())
            ->method('createCheckoutSession')
            ->with($fakeCartItems)
            ->willReturn('https://stripe.test/session');

        self::getContainer()->set(CartService::class, $cartService);
        self::getContainer()->set(StripeService::class, $stripeService);

        $client->request('GET', '/cart/checkout');

        self::assertResponseRedirects('https://stripe.test/session', 302);
    }

    public function testCheckoutRedirectsToCartWhenEmptyAndDoesNotCallStripe(): void
    {
        $client = static::createClient();

        /** @var CartService&MockObject $cartService */
        $cartService = $this->createMock(CartService::class);
        $cartService->method('getItems')->willReturn([]);
        $cartService->method('getTotal')->willReturn(0.0);

        /** @var StripeService&MockObject $stripeService */
        $stripeService = $this->createMock(StripeService::class);
        $stripeService->expects(self::never())->method('createCheckoutSession');

        self::getContainer()->set(CartService::class, $cartService);
        self::getContainer()->set(StripeService::class, $stripeService);

        $client->request('GET', '/cart/checkout');

        // controller fait redirectToRoute('cart_index') => /cart
        self::assertResponseRedirects('/cart', 302);
    }

    public function testSuccessClearsCartAndReturns200(): void
    {
        $client = static::createClient();

        /** @var CartService&MockObject $cartService */
        $cartService = $this->createMock(CartService::class);
        $cartService->expects(self::once())->method('clear');

        self::getContainer()->set(CartService::class, $cartService);

        $client->request('GET', '/cart/success');

        self::assertResponseIsSuccessful();
    }

    public function testCartIndexReturns200(): void
    {
        $client = static::createClient();

        /** @var CartService&MockObject $cartService */
        $cartService = $this->createMock(CartService::class);
        $cartService->method('getItems')->willReturn([]);
        $cartService->method('getTotal')->willReturn(0.0);

        self::getContainer()->set(CartService::class, $cartService);

        $client->request('GET', '/cart');

        self::assertResponseIsSuccessful();
    }
    public function testAddCallsCartServiceAndRedirects(): void
{
    $client = static::createClient();

    $cartService = $this->createMock(\App\Service\CartService::class);
    $cartService->expects(self::once())->method('add')->with(12, 'L');

    self::getContainer()->set(\App\Service\CartService::class, $cartService);

    $client->request('POST', '/cart/add/12', ['size' => 'L']);

    self::assertResponseRedirects('/cart', 302);
}

public function testRemoveCallsCartServiceAndRedirects(): void
{
    $client = static::createClient();

    $cartService = $this->createMock(\App\Service\CartService::class);
    $cartService->expects(self::once())->method('remove')->with(12, 'M');

    self::getContainer()->set(\App\Service\CartService::class, $cartService);

    $client->request('GET', '/cart/remove/12/M');

    self::assertResponseRedirects('/cart', 302);
}

public function testClearCallsCartServiceAndRedirects(): void
{
    $client = static::createClient();

    $cartService = $this->createMock(\App\Service\CartService::class);
    $cartService->expects(self::once())->method('clear');

    self::getContainer()->set(\App\Service\CartService::class, $cartService);

    $client->request('GET', '/cart/clear');

    self::assertResponseRedirects('/cart', 302);
}

}

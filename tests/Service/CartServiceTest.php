<?php

namespace App\Tests\Service;

use App\Repository\ProductRepository;
use App\Service\CartService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

final class CartServiceTest extends TestCase
{
    private function makeService(ProductRepository $productRepository, Session $session): CartService
    {
        $request = new Request();
        $request->setSession($session);

        $stack = new RequestStack();
        $stack->push($request);

        return new CartService($stack, $productRepository);
    }

    public function testAddIncrementsQuantityAndNormalizesSize(): void
    {
        $session = new Session(new MockArraySessionStorage());

        $product = $this->createMock(\stdClass::class);
        $product->method('getPrice')->willReturn(10.0);

        $repo = $this->createMock(ProductRepository::class);
        $repo->method('find')->willReturnMap([
            [1, $product],
        ]);

        $cart = $this->makeService($repo, $session);

        
        $cart->add(1, ' m ');
        $cart->add(1, 'M');

        
        $cart->add(1, 'XXL');

        $items = $cart->getItems();

        self::assertCount(1, $items);
        self::assertSame('M', $items[0]['size']);
        self::assertSame(3, $items[0]['quantity']);
        self::assertSame(30.0, $items[0]['subtotal']);
        self::assertSame(30.0, $cart->getTotal());
    }

    public function testAddSeparatesLinesBySize(): void
    {
        $session = new Session(new MockArraySessionStorage());

        $product = $this->createMock(\stdClass::class);
        $product->method('getPrice')->willReturn(20.0);

        $repo = $this->createMock(ProductRepository::class);
        $repo->method('find')->willReturnMap([
            [1, $product],
        ]);

        $cart = $this->makeService($repo, $session);

        $cart->add(1, 'S');
        $cart->add(1, 'S');
        $cart->add(1, 'L');

        $items = $cart->getItems();

        
        self::assertCount(2, $items);
        self::assertSame(60.0, $cart->getTotal()); // 2*20 + 1*20
    }

    public function testRemoveDeletesOnlyGivenSizeLine(): void
    {
        $session = new Session(new MockArraySessionStorage());

        $product = $this->createMock(\stdClass::class);
        $product->method('getPrice')->willReturn(15.0);

        $repo = $this->createMock(ProductRepository::class);
        $repo->method('find')->willReturnMap([
            [1, $product],
        ]);

        $cart = $this->makeService($repo, $session);

        $cart->add(1, 'S');
        $cart->add(1, 'L');

        $cart->remove(1, 'S');

        $items = $cart->getItems();
        self::assertCount(1, $items);
        self::assertSame('L', $items[0]['size']);
        self::assertSame(15.0, $cart->getTotal());
    }

    public function testClearEmptiesCart(): void
    {
        $session = new Session(new MockArraySessionStorage());

        $product = $this->createMock(\stdClass::class);
        $product->method('getPrice')->willReturn(5.0);

        $repo = $this->createMock(ProductRepository::class);
        $repo->method('find')->willReturnMap([
            [1, $product],
        ]);

        $cart = $this->makeService($repo, $session);

        $cart->add(1, 'M');
        self::assertSame(5.0, $cart->getTotal());

        $cart->clear();
        self::assertSame([], $cart->getItems());
        self::assertSame(0.0, $cart->getTotal());
    }

    public function testGetItemsSkipsMissingProducts(): void
    {
        $session = new Session(new MockArraySessionStorage());

        
        $repo = $this->createMock(ProductRepository::class);
        $repo->method('find')->willReturn(null);

        $cart = $this->makeService($repo, $session);

        $cart->add(999, 'M');

        self::assertSame([], $cart->getItems());
        self::assertSame(0.0, $cart->getTotal());
    }
}

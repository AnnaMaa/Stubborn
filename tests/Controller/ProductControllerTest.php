<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProductControllerTest extends WebTestCase
{
    public function testProductsIndexIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/products');

        self::assertResponseIsSuccessful();
    }

    public function testProductShowReturns200Or404(): void
    {
        $client = static::createClient();
        $client->request('GET', '/products/1');

        
        self::assertTrue(
            in_array($client->getResponse()->getStatusCode(), [200, 404]),
            'La page /products/1 doit répondre 200 si le produit existe, sinon 404.'
        );
    }
}
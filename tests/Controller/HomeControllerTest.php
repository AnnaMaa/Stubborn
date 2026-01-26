<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class HomeControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $url = static::getContainer()->get('router')->generate('app_home');
        $client->request('GET', $url);
        self::assertResponseIsSuccessful();

    }
}

<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $container = static::getContainer();

        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine')->getManager();
        $this->userRepository = $container->get(UserRepository::class);

        // Nettoyer la table user
        foreach ($this->userRepository->findAll() as $user) {
            $em->remove($user);
        }
        $em->flush();
    }

    public function testRegister(): void
    {
        
        $crawler = $this->client->request('GET', '/register');
        self::assertResponseIsSuccessful();

        
        $form = $crawler->filter('form[name="registration_form"]')->form();

        
        $this->client->submit($form, [
            'registration_form[username]' => 'johnny',
            'registration_form[email]' => 'me@example.com',
            'registration_form[deliveryAddress]' => '1 rue de Paris, 75000 Paris',

            
            'registration_form[plainPassword][first]' => 'password',
            'registration_form[plainPassword][second]' => 'password',

            
            'registration_form[agreeTerms]' => 1,
        ]);


        
        self::assertCount(1, $this->userRepository->findAll());
        $user = $this->userRepository->findAll()[0];

        
        self::assertFalse($user->isVerified());

        
        self::assertEmailCount(0);

        $messages = $this->getMailerMessages();
        self::assertCount(1, $messages);

        /** @var TemplatedEmail $templatedEmail */
        $templatedEmail = $messages[0];

        self::assertEmailAddressContains($templatedEmail, 'to', 'me@example.com');

        $messageBody = $templatedEmail->getHtmlBody();
        self::assertIsString($messageBody);

        // Récupère un lien de vérification, accepte URL absolue ou relative
        preg_match('#href="([^"]*(/verify/email[^"]+))"#', $messageBody, $matches);
        self::assertNotEmpty($matches, 'Lien de vérification introuvable dans l’email.');

        $verifyUrl = $matches[1];

        // 6) “Cliquer” le lien et vérifier que l’utilisateur est verified
        $this->client->request('GET', $verifyUrl);

        
        if ($this->client->getResponse()->isRedirection()) {
            $this->client->followRedirect();
        }

        self::assertTrue(
            static::getContainer()->get(UserRepository::class)->findAll()[0]->isVerified()
        );
    }
}

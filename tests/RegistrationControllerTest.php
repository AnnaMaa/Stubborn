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
        // 1) Ouvrir la page register
        $crawler = $this->client->request('GET', '/register');
        self::assertResponseIsSuccessful();

        // 2) Récupérer le formulaire (plus fiable que submitForm('Register', ...))
        $form = $crawler->filter('form[name="registration_form"]')->form();

        // 3) Soumettre avec TOUS les champs attendus + repeated password
        $this->client->submit($form, [
            'registration_form[username]' => 'johnny',
            'registration_form[email]' => 'me@example.com',
            'registration_form[deliveryAddress]' => '1 rue de Paris, 75000 Paris',

            // RepeatedType => first + second
            'registration_form[plainPassword][first]' => 'password',
            'registration_form[plainPassword][second]' => 'password',

            // Checkbox => 1 / true
            'registration_form[agreeTerms]' => 1,
        ]);

        // (optionnel) si ton controller redirect après inscription
        // self::assertResponseRedirects();
        // $this->client->followRedirect();

        // 4) L’utilisateur est bien créé
        self::assertCount(1, $this->userRepository->findAll());
        $user = $this->userRepository->findAll()[0];

        // Si ton User a bien isVerified() par défaut à false
        self::assertFalse($user->isVerified());

        // 5) Email de vérification envoyé (si tu utilises Mailer en mode test)
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

        // selon ton controller, il peut redirect
        if ($this->client->getResponse()->isRedirection()) {
            $this->client->followRedirect();
        }

        self::assertTrue(
            static::getContainer()->get(UserRepository::class)->findAll()[0]->isVerified()
        );
    }
}

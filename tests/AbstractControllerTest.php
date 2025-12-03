<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

Abstract class AbstractControllerTest extends PantherTestCase
{
    const ROLES =['ROLE_ADMIN'];
    const URL_LOGIN = '/fr/login';
    const SUBMIT_BUTTON = 'Sign in';
    const URL_ADMIN = '/fr/admin/';
    const CLASS_ALERT_DANGER = '.alert-danger';
    const MESSAGE_ALERT_DANGER = 'Identifiants invalides.';
    const ALERT_DANGER = [
        'class' => self::CLASS_ALERT_DANGER,
        'message' => self::MESSAGE_ALERT_DANGER,
    ];
    const EMAIL = 'email@societe.com';
    const PASSWORD = 'password';
    const EMAIL_BAD = 'doesNotExist@example.com';
    const PASSWORD_BAD = 'bad-password';

    protected Client $client;

    protected function setUp(): void
    {
        $container = static::getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $userRepository = $em->getRepository(User::class);

        // Remove any existing users from the test database
        foreach ($userRepository->findAll() as $user) {
            $em->remove($user);
        }

        $em->flush();

        // Create a User fixture
        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = $container->get('security.user_password_hasher');

        $user = (new User())->setEmail(self::EMAIL);
        $user->setPassword($passwordHasher->hashPassword($user, self::PASSWORD));
        $user->setRoles(self::ROLES);

        $em->persist($user);

        $em->flush();


        $this->initConfigAndTemplate();

        $this->client = static::createPantherClient();

    }

    public function initConfigAndTemplate()
    {
        // Obtenir le conteneur de services
        $kernel = self::bootKernel();
        // Trouver la commande via le service
        $command = $kernel->getContainer()->get('app:init-hermes');

        $commandTester = new CommandTester($command);
        
        // Exécuter la commande
        $commandTester->execute();

        // Vérifiez le résultat de l'exécution
        $output = $commandTester->getDisplay();

        // // Accéder au conteneur de services
        // $command = $this->getContainer()->get('app:init-hermes'); // Assurez-vous que le service de votre commande est enregistré

        // // Exécuter la commande
        // $command->run(); // Remplacez par la méthode d'exécution de votre commande
    }

    protected function tearDown(): void
    {
        if ($this->client instanceof PantherTestCase) {
            $this->client->quit();
        }
        parent::tearDown();
    }

    public function login($username = self::EMAIL , $password = self::PASSWORD): void
    {
        $this->client->request('GET', self::URL_LOGIN);
        $this->assertSelectorTextContains('[id="signin"]', 'Please sign in');

        $this->client->submitForm(self::SUBMIT_BUTTON, [
            '_username' => $username,
            '_password' => $password,
        ]);

    }

}

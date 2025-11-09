<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AbstractControllerTest extends WebTestCase
{
    const ROLES =['ROLE_ADMIN'];
    const URL_LOGIN = '/fr/login';
    const URL_ADMIN = '/fr/admin/';
    const CLASS_ALERT_DANGER = '.alert-danger';
    const ALERT_DANGER = 'Identifiants invalides.';
    const EMAIL = 'email@societe.com';
    const EMAIL_BAD = 'doesNotExist@example.com';
    const PASSWORD = 'password';
    const PASSWORD_BAD = 'bad-password';

    private KernelBrowser $client;

    protected function setUp(): void
    {
        static::createClient();
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

    }

    public function login($username = self::EMAIL , $password = self::PASSWORD): void
    {
        $this->client = static::getClient();
        $this->client->request('GET', self::URL_LOGIN);
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Sign in', [
            '_username' => $username,
            '_password' => $password,
        ]);
    }

}

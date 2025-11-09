<?php

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTest;

class LoginControllerTest extends AbstractControllerTest
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::getClient();
    }


    public function testLogin(): void
    {
        // Denied - Can't login with invalid email address.
        $this->login(self::EMAIL_BAD, self::PASSWORD);
        self::assertResponseRedirects(self::URL_LOGIN);
        $this->client->followRedirect();
        // Ensure we do not reveal if the user exists or not.
        self::assertSelectorTextContains(self::CLASS_ALERT_DANGER, self::ALERT_DANGER);

        // Denied - Can't login with invalid password.
        $this->login(self::EMAIL, self::PASSWORD_BAD);
        self::assertResponseRedirects(self::URL_LOGIN);
        $this->client->followRedirect();
        // Ensure we do not reveal the user exists but the password is wrong.
        self::assertSelectorTextContains(self::CLASS_ALERT_DANGER, self::ALERT_DANGER);

        // Success - Login with valid credentials is allowed.
        $this->login(self::EMAIL, self::PASSWORD);
        self::assertResponseRedirects(self::URL_ADMIN);
        $this->client->followRedirect();
        self::assertSelectorNotExists(self::CLASS_ALERT_DANGER);
        self::assertResponseIsSuccessful();
    }
}

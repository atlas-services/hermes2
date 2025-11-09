<?php

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTest;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Symfony\Bundle\Panther\PantherTestCase;

class LoginControllerTest extends AbstractControllerTest
{
    protected function tearDown(): void
    {
        if ($this->client instanceof \Symfony\Component\Panther\PantherTestCase) {
            $this->client->quit();
        }
        parent::tearDown();
    }

    public function testBadLogin(): void
    {
        try{

            $this->login(self::EMAIL_BAD, self::PASSWORD);
            $this->client->waitFor(self::ALERT_DANGER['class']);
            $this->assertSelectorTextContains(self::ALERT_DANGER['class'], self::ALERT_DANGER['message']);

            $this->login(self::EMAIL, self::PASSWORD_BAD);
            $this->client->waitFor(self::ALERT_DANGER['class']);
            $this->assertSelectorTextContains(self::ALERT_DANGER['class'], self::ALERT_DANGER['message']);

        } catch (\Exception $e) {
             throw $e;
        }
    }

    public function testGoodLogin(): void
    {
        try{
            $this->login(self::EMAIL, self::PASSWORD);
            // Suivre la redirection après la soumission du formulaire
            $this->client->waitFor('#cms_complet'); // Attendre que l'élément de bienvenue apparaisse
            // Vérifiez que l'utilisateur est redirigé vers la bonne page
            $this->assertSelectorTextContains('#cms_complet', 'Cms'); // Vérifiez que le texte est correct

        } catch (\Exception $e) {
            throw $e;
        }
    }
}

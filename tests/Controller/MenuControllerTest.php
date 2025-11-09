<?php

namespace App\Tests\Controller;

use App\Entity\Menu;
use App\Tests\AbstractControllerTest;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

final class MenuControllerTest extends AbstractControllerTest
{
    const MENUS_INDEX = 'Menus';
    const MENU_SHOW = 'Show Menu';
    const MENU_EDIT = 'Edit Menu';
    const BUTTON_CREATE = "Créer";
    const BUTTON_UPDATE = "Mettre à jour";
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $menuRepository;
    private string $path = '/fr/admin/menu/';

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->menuRepository = $this->manager->getRepository(Menu::class);

        foreach ($this->menuRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
        $this->client = static::getClient();
    }

    public function addDbMenu($name)
    {
        $fixture = new Menu();
        $fixture->setName($name);
        $this->manager->persist($fixture);
        $this->manager->flush();

        return $fixture;
    }

    public function testIndex(): void
    {
        $this->login();
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains(self::MENUS_INDEX);

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->login();
        // $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm(self::BUTTON_CREATE, [
            'menu[name]' => 'Testing',
            // 'menu[position]' => 1,
            // 'menu[slug]' => 'Testing',
            'menu[active]' => true,
            // 'menu[parent]' => 'Testing',
        ]);

        // self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->menuRepository->count([]));
    }

    public function testShow(): void
    {
        // $this->markTestIncomplete();
        $this->login();
        $fixture = $this->addDbMenu('My Name');
        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains(self::MENU_SHOW);

    }

    public function testEdit(): void
    {
        // $this->markTestIncomplete();
        $this->login();
        $fixture = $this->addDbMenu('My Name');

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm(self::BUTTON_UPDATE, [
            'menu[name]' => 'Something New',
            // 'menu[position]' => 3,
            // 'menu[slug]' => 'Something New',
            'menu[active]' => true,
            // 'menu[parent]' => 'Something New',
        ]);

        // self::assertResponseRedirects($this->path);

        $fixture = $this->menuRepository->findById($fixture->getId());

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame( 2, $fixture[0]->getPosition());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame(true, $fixture[0]->getActive());
        // self::assertSame('Something New', $fixture[0]->getParent());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Menu();
        $fixture->setName('Value');
        $fixture->setPosition('Value');
        $fixture->setSlug('Value');
        $fixture->setActive('Value');
        $fixture->setParent('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/menu/');
        self::assertSame(0, $this->menuRepository->count([]));
    }
}

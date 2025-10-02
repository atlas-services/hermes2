<?php

namespace App\Tests\Controller;

use App\Entity\Menu;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MenuControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $menuRepository;
    private string $path = '/menu/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->menuRepository = $this->manager->getRepository(Menu::class);

        foreach ($this->menuRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Menu index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'menu[name]' => 'Testing',
            'menu[position]' => 'Testing',
            'menu[slug]' => 'Testing',
            'menu[active]' => 'Testing',
            'menu[parent]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->menuRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Menu();
        $fixture->setName('My Title');
        $fixture->setPosition('My Title');
        $fixture->setSlug('My Title');
        $fixture->setActive('My Title');
        $fixture->setParent('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Menu');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
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

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'menu[name]' => 'Something New',
            'menu[position]' => 'Something New',
            'menu[slug]' => 'Something New',
            'menu[active]' => 'Something New',
            'menu[parent]' => 'Something New',
        ]);

        self::assertResponseRedirects('/menu/');

        $fixture = $this->menuRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getPosition());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame('Something New', $fixture[0]->getActive());
        self::assertSame('Something New', $fixture[0]->getParent());
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

<?php

namespace App\Tests\Controller;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MessageControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private MessageRepository $repository;
    private string $path = '/message/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Message::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Message index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'message[title]' => 'Testing',
            'message[description]' => 'Testing',
            'message[color]' => 'Testing',
            'message[startAt]' => 'Testing',
            'message[endAt]' => 'Testing',
            'message[addedAt]' => 'Testing',
            'message[updatedAt]' => 'Testing',
            'message[isActive]' => 'Testing',
        ]);

        self::assertResponseRedirects('/message/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Message();
        $fixture->setTitle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setColor('My Title');
        $fixture->setStartAt('My Title');
        $fixture->setEndAt('My Title');
        $fixture->setAddedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setIsActive('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Message');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Message();
        $fixture->setTitle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setColor('My Title');
        $fixture->setStartAt('My Title');
        $fixture->setEndAt('My Title');
        $fixture->setAddedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setIsActive('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'message[title]' => 'Something New',
            'message[description]' => 'Something New',
            'message[color]' => 'Something New',
            'message[startAt]' => 'Something New',
            'message[endAt]' => 'Something New',
            'message[addedAt]' => 'Something New',
            'message[updatedAt]' => 'Something New',
            'message[isActive]' => 'Something New',
        ]);

        self::assertResponseRedirects('/message/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getColor());
        self::assertSame('Something New', $fixture[0]->getStartAt());
        self::assertSame('Something New', $fixture[0]->getEndAt());
        self::assertSame('Something New', $fixture[0]->getAddedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getIsActive());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Message();
        $fixture->setTitle('My Title');
        $fixture->setDescription('My Title');
        $fixture->setColor('My Title');
        $fixture->setStartAt('My Title');
        $fixture->setEndAt('My Title');
        $fixture->setAddedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setIsActive('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/message/');
    }
}

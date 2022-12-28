<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Generator;
use JG\SonataBatchEntityImportBundle\Tests\DatabaseLoader;
use JG\SonataBatchEntityImportBundle\Tests\Fixtures\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImportControllerTraitTest extends WebTestCase
{
    private const URL = '/jg_sonata_batch_entity_import_bundle/user/import';
    private const URL_DEPRECATED = '/jg_sonata_batch_entity_import_bundle/user_for_deprecated_config/import';
    protected KernelBrowser $client;
    protected ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->entityManager = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $databaseLoader = self::$kernel->getContainer()->get(DatabaseLoader::class);
        $databaseLoader->reload();
    }

    /**
     * @dataProvider urlProvider
     */
    public function testControllerWorksOk(string $url): void
    {
        $repository = $this->entityManager->getRepository(User::class);
        self::assertEmpty($repository->findAll());

        $this->client->request('GET', $url);
        self::assertTrue($this->client->getResponse()->isSuccessful());

        $uploadedFile = __DIR__ . '/../Fixtures/Resources/test.csv';
        $this->client->submitForm('btn-submit', ['file_import[file]' => $uploadedFile]);

        self::assertTrue($this->client->getResponse()->isSuccessful());
        self::assertEquals($url, $this->client->getRequest()->getRequestUri());

        $this->client->submitForm('btn-submit');

        self::assertTrue($this->client->getResponse()->isRedirect($url));
        $this->client->followRedirect();
        self::assertTrue($this->client->getResponse()->isSuccessful());
        self::assertStringContainsString('Data has been imported', $this->client->getResponse()->getContent());

        /** @var User[]|array $user */
        $users = $repository->findAll();
        self::assertCount(2, $users);
        self::assertEquals('user_1', $users[0]->getName());
        self::assertEquals('user_2', $users[1]->getName());
    }

    public function urlProvider(): Generator
    {
        yield [self::URL];
        yield [self::URL_DEPRECATED];
    }

    public function testDefaultMethods(): void
    {
        $this->client->request('GET', '/jg_sonata_batch_entity_import_bundle/user/list');
        self::assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->request('GET', '/jg_sonata_batch_entity_import_bundle/user/create');
        self::assertTrue($this->client->getResponse()->isSuccessful());
    }
}

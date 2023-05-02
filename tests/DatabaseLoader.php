<?php

declare(strict_types=1);

namespace JG\SonataBatchEntityImportBundle\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

class DatabaseLoader
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, Connection $connection)
    {
        $this->entityManager = $entityManager;

        $connection->getConfiguration()->setSQLLogger();
    }

    public function reload(): void
    {
        $allMetadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $entityClasses = [];
        foreach ($allMetadata as $classMetadata) {
            $entityClasses[] = $classMetadata->getName();
        }

        $this->reloadEntityClasses($entityClasses);
    }

    public function reloadEntityClasses(array $entityClasses): void
    {
        $schema = [];
        foreach ($entityClasses as $entityClass) {
            $schema[] = $this->entityManager->getClassMetadata($entityClass);
        }

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($schema);
        $schemaTool->createSchema($schema);
    }
}

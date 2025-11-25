<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Integration\MySQL;

use Doctrine\ORM\EntityManagerInterface;
use VM\Tests\Infrastructure\Integration\BaseKernelTestCase;

abstract class RepositoryTestCase extends BaseKernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
        parent::setUp();
    }

    abstract protected function purge(): void;

    protected function purgeTables(string ...$tables): void
    {
        $connection = $this->em()->getConnection();

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0;');
        foreach ($tables as $tableName) {
            $truncateSql = $connection->getDatabasePlatform()->getTruncateTableSQL($tableName);
            $connection->executeStatement($truncateSql);
        }
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1;');
    }

    final protected function em(): EntityManagerInterface
    {
        return self::getContainer()->get(EntityManagerInterface::class);
    }
}

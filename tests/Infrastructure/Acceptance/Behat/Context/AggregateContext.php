<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Acceptance\Behat\Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use VM\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;
use VM\Tests\DataFixtures\DataLoaders\Fixtures;
use VM\Tests\DataFixtures\Purger\CustomPurger;
use VM\Tests\Infrastructure\Acceptance\Behat\Context\BaseContext;

abstract class AggregateContext extends BaseContext
{
    protected AggregateRepository $repository;
    protected ORMExecutor $ormExecutor;

    public function __construct(KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->ormExecutor = new ORMExecutor($this->em(), new CustomPurger());
    }

    abstract protected function purge(): void;

    /**
     * @BeforeScenario @truncateDatabaseTables
     */
    public function cleanDB(BeforeScenarioScope $event): void
    {
        $this->purge();
    }

    protected function loadFixtures(Fixtures ...$fixtures)
    {
        $fixtureLoader = new Loader();
        foreach ($fixtures as $fixture) {
            $fixtureLoader->addFixture($fixture);
        }

        $this->ormExecutor->execute($fixtureLoader->getFixtures(), true);
    }

    protected function purgeTables(string ...$tables): void
    {
        $connection = $this->em()->getConnection();

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0');
        foreach ($tables as $tableName) {
            $truncateSql = $connection->getDatabasePlatform()->getTruncateTableSQL($tableName);
            $connection->executeStatement($truncateSql);
        }
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1');
    }

    final protected function em(): EntityManagerInterface
    {
        return $this->getContainer()->get(EntityManagerInterface::class);
    }

    protected function getContainer(): ContainerInterface
    {
        return $this->kernel->getContainer()->get('test.service_container');
    }
}

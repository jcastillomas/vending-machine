<?php

declare(strict_types=1);

namespace VM\Tests\DataFixtures\Purger;

use Doctrine\Common\DataFixtures\Purger\ORMPurgerInterface;
use Doctrine\ORM\EntityManagerInterface;

final class CustomPurger implements ORMPurgerInterface
{
    private ?EntityManagerInterface $em;

    public function purge(): void
    {
        $connection = $this->em->getConnection();

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0');
        $connection->executeStatement($this->tables());
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function tables(): string
    {
        return <<<SQL
DELETE FROM cash;
DELETE FROM cash_cash_item;
DELETE FROM cash_item;
DELETE FROM currency;
DELETE FROM doctrine_migration_versions;
DELETE FROM fund;
DELETE FROM fund_cash_item;
DELETE FROM product;
DELETE FROM stock;
DELETE FROM stock_item;
DELETE FROM stock_stock_item;
DELETE FROM vending_machine;
SQL;
    }

    public function setEntityManager(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }
}

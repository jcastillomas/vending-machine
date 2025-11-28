<?php

declare(strict_types=1);

namespace VM\Context\Product\Infrastructure\Write\Persistence\Doctrine\MySQL\Repository;

use VM\Context\Product\Domain\Write\Aggregate\Stock;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductId;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\StockId;
use VM\Context\Product\Domain\Write\Repository\StockRepository;
use VM\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;

class DoctrineStockRepository extends AggregateRepository implements StockRepository
{
    public function save(Stock $stock): void
    {
        $this->saveAggregate($stock);
    }

    public function find(StockId $stockId): Stock
    {
        return $this->doFind($stockId);
    }

    public function findVendingMachine(): Stock
    {
        return $this->doSearchByCriteria([])[0];
    }

    public function findByProductId(ProductId $id): Stock
    {
        $qb = $this->getRepository()->createQueryBuilder('Stock');
        $qb->innerJoin('Stock.stockItems', 'StockItems');
        $qb->andWhere("StockItems.productId = :id")
            ->setParameter('id', $id->value());
        $qb->select('Stock');
        return $qb->getQuery()->getResult()[0];
    }

    protected function entityClassName(): string
    {
        return Stock::class;
    }
}

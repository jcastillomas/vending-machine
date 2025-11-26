<?php

declare(strict_types=1);

namespace VM\Tests\Integration\Context\Product\Infrastructure\Write\Persistence\Doctrine\MySQL\Repository;

use VM\Context\Product\Domain\Write\Aggregate\Stock;
use VM\Context\Product\Domain\Write\Repository\StockRepository;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\StockIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Entity\StockItemsStub;
use VM\Tests\Infrastructure\Integration\MySQL\AggregateRepositoryTestCase;
use VM\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;

final class DoctrineStockRepositoryTest extends AggregateRepositoryTestCase
{
    public function test_it_saves_and_finds_stock(): void
    {
        $expectedStock = $this->givenAStockWith();
        $this->whenAStockIsSaved($expectedStock);
        $this->thenAStockIsFound($expectedStock);
    }

    private function givenAStockWith(): Stock
    {
        return Stock::create(
            StockIdStub::random(),
            VendingMachineIdStub::random(),
            StockItemsStub::random()
        );
    }

    private function whenAStockIsSaved(Stock $stock): void
    {
        $this->repository->save($stock);
        $this->em()->flush();
        $this->em()->clear();
    }

    private function thenAStockIsFound(Stock $expectedStock): void
    {
        $actualStock = $this->repository->find($expectedStock->id());
        $this->assertEquals($expectedStock->id(), $actualStock->id());
        $this->assertEquals($expectedStock->vendingMachineId(), $actualStock->vendingMachineId());
        $this->assertEquals($expectedStock->stockItems()->count(), $actualStock->stockItems()->count());
        $this->assertEquals($expectedStock->createdAt()->getTimestamp(), $actualStock->createdAt()->getTimestamp());
        $this->assertEquals($expectedStock->updatedAt()?->getTimestamp(), $actualStock->updatedAt()?->getTimestamp());
    }

    protected function repository(): AggregateRepository
    {
        return self::getContainer()->get('test.' . StockRepository::class);
    }

    protected function purge(): void
    {
        $this->purgeTables( 'stock', 'stock_stock_item', 'stock_item');
    }
}

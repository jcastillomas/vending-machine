<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Product\Domain\Write\Aggregate;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use VM\Context\Product\Domain\Write\Aggregate\Stock;
use VM\Context\Product\Domain\Write\Entity\StockItem;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\StockIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Entity\StockItemsStub;

class StockTest extends TestCase
{
    public function test_it_creates_a_stock(): void
    {
        $id = StockIdStub::random();
        $vendingMachineId = VendingMachineIdStub::random();
        $stockItems = StockItemsStub::random();
        $datetime = new DateTimeImmutable('-1 Day');

        $stock = Stock::create(
            $id,
            $vendingMachineId,
            $stockItems
        );

        $this->assertTrue($id->equalsTo($stock->id()));
        $this->assertTrue($vendingMachineId->equalsTo($stock->vendingMachineId()));
        foreach ($stock->stockItems() as $item) {
            $this->assertTrue($item instanceof StockItem);
        }
        $this->assertGreaterThan($datetime->getTimestamp(), $stock->createdAt()->getTimestamp());
        $this->assertEquals($stock->updatedAt(), null);
    }
}

<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Product\Domain\Write\Entity;

use PHPUnit\Framework\TestCase;
use VM\Context\Product\Domain\Write\Entity\StockItem;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Entity\ValueObject\AmountStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Entity\ValueObject\StockItemIdStub;

class StockItemTest extends TestCase
{
    public function test_it_creates_a_stock_item(): void
    {
        $id = StockItemIdStub::random();
        $productId = ProductIdStub::random();
        $amount = AmountStub::random();
        $datetime = new \DateTimeImmutable('-1 Day');

        $stockItem = StockItem::create(
            $id,
            $productId,
            $amount
        );

        $this->assertTrue($id->equalsTo($stockItem->id()));
        $this->assertTrue($productId->equalsTo($stockItem->productId()));
        $this->assertTrue($amount->equalsTo($stockItem->amount()));
        $this->assertGreaterThan($datetime->getTimestamp(), $stockItem->createdAt()->getTimestamp());
        $this->assertEquals($stockItem->updatedAt(), null);
    }
}

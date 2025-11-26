<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Payment\Domain\Write\Aggregate;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use VM\Context\Payment\Domain\Write\Aggregate\Cash;
use VM\Context\Payment\Domain\Write\Entity\CashItem;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CashIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\CashItemsStub;

class CashTest extends TestCase
{
    public function test_it_creates_a_currency(): void
    {
        $id = CashIdStub::random();
        $vendingMachineId = VendingMachineIdStub::random();
        $cashItems = CashItemsStub::random();
        $datetime = new DateTimeImmutable('-1 Day');

        $cash = Cash::create(
            $id,
            $vendingMachineId,
            $cashItems
        );

        $this->assertTrue($id->equalsTo($cash->id()));
        $this->assertTrue($vendingMachineId->equalsTo($cash->vendingMachineId()));
        foreach ($cash->cashItems() as $item) {
            $this->assertTrue($item instanceof CashItem);
        }
        $this->assertGreaterThan($datetime->getTimestamp(), $cash->createdAt()->getTimestamp());
        $this->assertEquals($cash->updatedAt(), null);
    }
}

<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Payment\Domain\Write\Entity;

use PHPUnit\Framework\TestCase;
use VM\Context\Payment\Domain\Write\Entity\CashItem;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\ValueObject\AmountStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\ValueObject\CashItemIdStub;

class CashItemTest extends TestCase
{
    public function test_it_creates_a_cash_item(): void
    {
        $id = CashItemIdStub::random();
        $currencyId = CurrencyIdStub::random();
        $amount = AmountStub::random();
        $datetime = new \DateTimeImmutable('-1 Day');

        $cashItem = CashItem::create(
            $id,
            $currencyId,
            $amount
        );

        $this->assertTrue($id->equalsTo($cashItem->id()));
        $this->assertTrue($currencyId->equalsTo($cashItem->currencyId()));
        $this->assertTrue($amount->equalsTo($cashItem->amount()));
        $this->assertGreaterThan($datetime->getTimestamp(), $cashItem->createdAt()->getTimestamp());
        $this->assertEquals($cashItem->updatedAt(), null);
    }
}

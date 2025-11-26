<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Payment\Domain\Write\Aggregate;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use VM\Context\Payment\Domain\Write\Aggregate\Currency;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyKindStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValueStub;

class CurrencyTest extends TestCase
{
    public function test_it_creates_a_currency(): void
    {
        $id = CurrencyIdStub::random();
        $value = CurrencyValueStub::random();
        $kind = CurrencyKindStub::random();
        $datetime = new DateTimeImmutable('-1 Day');

        $vendingMachine = Currency::create(
            $id,
            $value,
            $kind
        );

        $this->assertTrue($id->equalsTo($vendingMachine->id()));
        $this->assertTrue($value->equalsTo($vendingMachine->currencyValue()));
        $this->assertTrue($kind->equalsTo($vendingMachine->currencyKind()));
        $this->assertGreaterThan($datetime->getTimestamp(), $vendingMachine->createdAt()->getTimestamp());
        $this->assertEquals($vendingMachine->updatedAt(), null);
    }
}

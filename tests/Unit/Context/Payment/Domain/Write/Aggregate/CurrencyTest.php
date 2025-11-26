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

        $currency = Currency::create(
            $id,
            $value,
            $kind
        );

        $this->assertTrue($id->equalsTo($currency->id()));
        $this->assertTrue($value->equalsTo($currency->value()));
        $this->assertTrue($kind->equalsTo($currency->kind()));
        $this->assertGreaterThan($datetime->getTimestamp(), $currency->createdAt()->getTimestamp());
        $this->assertEquals($currency->updatedAt(), null);
    }
}

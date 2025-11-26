<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Product\Domain\Write\Aggregate;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use VM\Context\Product\Domain\Write\Aggregate\Product;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductNameStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductValueStub;

class ProductTest extends TestCase
{
    public function test_it_creates_a_currency(): void
    {
        $id = ProductIdStub::random();
        $value = ProductValueStub::random();
        $name = ProductNameStub::random();
        $datetime = new DateTimeImmutable('-1 Day');

        $currency = Product::create(
            $id,
            $name,
            $value
        );

        $this->assertTrue($id->equalsTo($currency->id()));
        $this->assertTrue($value->equalsTo($currency->value()));
        $this->assertTrue($name->equalsTo($currency->name()));
        $this->assertGreaterThan($datetime->getTimestamp(), $currency->createdAt()->getTimestamp());
        $this->assertEquals($currency->updatedAt(), null);
    }
}

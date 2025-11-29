<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Product\Domain\Write\Aggregate\ValueObject;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductValue;
use VM\Shared\Domain\Service\Assertion\AssertionFailedException;

final class ProductValueTest extends TestCase
{
    const MIN = 0.001;

    public function test_it_throws_exception_when_zero_is_given(): void
    {
        $this->expectException(AssertionFailedException::class);
        ProductValue::fromString(0);
    }

    public function test_it_returns_a_value_when_valid_float_is_given(): void
    {
        $faker = Factory::create();
        $float = $faker->randomFloat(min: self::MIN);
        $actualValue = ProductValue::fromFloat($float);
        $this->assertEquals($float, $actualValue->value());
    }
}

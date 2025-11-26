<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Payment\Domain\Write\Aggregate\ValueObject;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValue;
use VM\Shared\Domain\Service\Assertion\AssertionFailedException;

final class CurrencyValueTest extends TestCase
{
    public function test_it_throws_exception_when_zero_is_given(): void
    {
        $this->expectException(AssertionFailedException::class);
        CurrencyValue::fromString(0);
    }

    public function test_it_returns_a_value_when_valid_string_is_given(): void
    {
        $faker = Factory::create();
        $float = $faker->randomFloat(min: 1);
        $actualValue = CurrencyValue::fromFloat($float);
        $this->assertEquals($float, $actualValue->value());
    }
}

<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Payment\Domain\Write\Aggregate\ValueObject;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyKind;
use VM\Shared\Domain\Service\Assertion\AssertionFailedException;

final class CurrencyKindTest extends TestCase
{
    public function test_it_throws_exception_when_empty_string_is_given(): void
    {
        $this->expectException(AssertionFailedException::class);
        CurrencyKind::fromString('');
    }

    public function test_it_returns_a_value_when_valid_string_is_given(): void
    {
        $faker = Factory::create();
        $string = $faker->currencyCode();
        $actualValue = CurrencyKind::fromString($string);
        $this->assertEquals($string, $actualValue->value());
    }
}

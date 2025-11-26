<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Product\Domain\Write\Aggregate\ValueObject;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductName;
use VM\Shared\Domain\Service\Assertion\AssertionFailedException;

final class ProductNameTest extends TestCase
{
    public function test_it_throws_exception_when_empty_string_is_given(): void
    {
        $this->expectException(AssertionFailedException::class);
        ProductName::fromString('');
    }

    public function test_it_returns_a_value_when_valid_string_is_given(): void
    {
        $faker = Factory::create();
        $string = $faker->name();
        $actualValue = ProductName::fromString($string);
        $this->assertEquals($string, $actualValue->value());
    }
}

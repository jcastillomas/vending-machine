<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Payment\Domain\Write\Entity\ValueObject;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use VM\Context\Payment\Domain\Write\Entity\ValueObject\Amount;
use VM\Shared\Domain\Service\Assertion\AssertionFailedException;

final class AmountTest extends TestCase
{
    public function test_it_throws_exception_when_zero_is_given(): void
    {
        $this->expectException(AssertionFailedException::class);
        Amount::fromInt(-1);
    }

    public function test_it_returns_a_value_when_valid_string_is_given(): void
    {
        $faker = Factory::create();
        $int = $faker->randomNumber();
        $actualValue = Amount::fromInt($int);
        $this->assertEquals($int, $actualValue->value());
    }
}

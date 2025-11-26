<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject;

use Faker\Factory;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValue;

final class CurrencyValueStub
{
    public static function create(float $value): CurrencyValue
    {
        return CurrencyValue::fromFloat($value);
    }

    public static function random(): CurrencyValue
    {
        $faker = Factory::create();

        return CurrencyValue::fromFloat($faker->randomFloat(null, 0.001));
    }
}

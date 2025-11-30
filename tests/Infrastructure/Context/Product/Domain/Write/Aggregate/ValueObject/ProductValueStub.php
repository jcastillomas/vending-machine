<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject;

use Faker\Factory;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductValue;

final class ProductValueStub
{
    const MIN_VALUE = 0.001;

    public static function create(float $value): ProductValue
    {
        return ProductValue::fromFloat($value);
    }

    public static function random(): ProductValue
    {
        $faker = Factory::create();

        return ProductValue::fromFloat($faker->randomFloat(3, self::MIN_VALUE));
    }
}

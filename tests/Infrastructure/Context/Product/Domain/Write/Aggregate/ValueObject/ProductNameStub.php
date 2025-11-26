<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject;

use Faker\Factory;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductName;

final class ProductNameStub
{
    public static function create(string $name): ProductName
    {
        return ProductName::fromString($name);
    }

    public static function random(): ProductName
    {
        $faker = Factory::create();

        return ProductName::fromString($faker->name());
    }
}

<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Product\Domain\Write\Entity\ValueObject;

use Faker\Factory;
use VM\Context\Product\Domain\Write\Entity\ValueObject\Amount;

final class AmountStub
{
    public static function random(): Amount
    {
        $faker = Factory::create();
        return Amount::fromInt($faker->randomNumber() + 1);
    }
}

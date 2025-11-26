<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject;

use Faker\Factory;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyKind;

final class CurrencyKindStub
{
    public static function create(string $name): CurrencyKind
    {
        return CurrencyKind::fromString($name);
    }

    public static function random(): CurrencyKind
    {
        $faker = Factory::create();

        return CurrencyKind::fromString($faker->currencyCode());
    }
}

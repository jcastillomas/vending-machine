<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity;

use Faker\Factory;
use VM\Context\Payment\Domain\Write\Entity\CashItems;

final class CashItemsStub
{
    public static function random(): CashItems
    {
        $cashItems = CashItems::createEmpty();
        $faker = Factory::create();
        $times = $faker->randomNumber(1);

        foreach (range(0, $times) as $step) {
            $cashItems->add(CashItemStub::random());
        }

        return $cashItems;
    }
}

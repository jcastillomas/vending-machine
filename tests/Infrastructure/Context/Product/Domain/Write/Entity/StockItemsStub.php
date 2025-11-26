<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Product\Domain\Write\Entity;

use Faker\Factory;
use VM\Context\Product\Domain\Write\Entity\StockItems;

final class StockItemsStub
{
    public static function random(): StockItems
    {
        $cashItems = StockItems::createEmpty();
        $faker = Factory::create();
        $times = $faker->randomNumber(1);

        foreach (range(0, $times) as $step) {
            $cashItems->add(StockItemStub::random());
        }

        return $cashItems;
    }
}

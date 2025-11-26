<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Product\Domain\Write\Entity;

use VM\Context\Product\Domain\Write\Entity\StockItem;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Entity\ValueObject\AmountStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Entity\ValueObject\StockItemIdStub;

final class StockItemStub
{
    public static function random(): StockItem
    {
        return StockItem::create(
            StockItemIdStub::random(),
            ProductIdStub::random(),
            AmountStub::random()
        );
    }
}

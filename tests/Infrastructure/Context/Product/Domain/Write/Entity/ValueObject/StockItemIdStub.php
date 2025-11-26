<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Product\Domain\Write\Entity\ValueObject;

use VM\Context\Product\Domain\Write\Entity\ValueObject\StockItemId;

final class StockItemIdStub
{
    public static function random(): StockItemId
    {
        return StockItemId::generate();
    }
}

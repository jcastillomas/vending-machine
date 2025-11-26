<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject;

use VM\Context\Product\Domain\Write\Aggregate\ValueObject\StockId;

final class StockIdStub
{
    public static function random(): StockId
    {
        return StockId::generate();
    }
}

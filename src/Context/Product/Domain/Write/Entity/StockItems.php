<?php

declare(strict_types=1);

namespace VM\Context\Product\Domain\Write\Entity;

use VM\Shared\Domain\TypedCollection;

class StockItems extends TypedCollection
{
    protected function type(): string
    {
        return StockItem::class;
    }
}

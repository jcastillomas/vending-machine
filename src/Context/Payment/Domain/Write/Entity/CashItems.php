<?php

declare(strict_types=1);

namespace VM\Context\Payment\Domain\Write\Entity;

use VM\Shared\Domain\TypedCollection;

class CashItems extends TypedCollection
{
    protected function type(): string
    {
        return CashItem::class;
    }
}

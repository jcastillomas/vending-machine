<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity;

use VM\Context\Payment\Domain\Write\Entity\CashItem;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\ValueObject\AmountStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\ValueObject\CashItemIdStub;

final class CashItemStub
{
    public static function random(): CashItem
    {
        return CashItem::create(
            CashItemIdStub::random(),
            CurrencyIdStub::random(),
            AmountStub::random()
        );
    }
}

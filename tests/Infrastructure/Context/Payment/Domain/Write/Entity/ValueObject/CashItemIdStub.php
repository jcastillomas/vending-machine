<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\ValueObject;

use VM\Context\Payment\Domain\Write\Entity\ValueObject\CashItemId;

final class CashItemIdStub
{
    public static function random(): CashItemId
    {
        return CashItemId::generate();
    }
}

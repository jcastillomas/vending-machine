<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject;

use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CashId;

final class CashIdStub
{
    public static function random(): CashId
    {
        return CashId::generate();
    }
}

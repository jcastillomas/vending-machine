<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject;

use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyId;

final class CurrencyIdStub
{
    public static function random(): CurrencyId
    {
        return CurrencyId::generate();
    }
}

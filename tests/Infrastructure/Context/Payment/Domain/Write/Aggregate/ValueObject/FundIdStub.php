<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject;

use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\FundId;

final class FundIdStub
{
    public static function random(): FundId
    {
        return FundId::generate();
    }
}

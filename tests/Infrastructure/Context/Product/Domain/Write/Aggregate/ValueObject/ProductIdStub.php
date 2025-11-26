<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject;

use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductId;

final class ProductIdStub
{
    public static function random(): ProductId
    {
        return ProductId::generate();
    }
}

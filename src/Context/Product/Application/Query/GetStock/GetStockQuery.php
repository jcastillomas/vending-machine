<?php

declare(strict_types=1);

namespace VM\Context\Product\Application\Query\GetStock;

use VM\Shared\Application\Bus\Query\Query;

final class GetStockQuery extends Query
{

    public static function create(): self {
        return new self([]);
    }

    protected function version(): string
    {
        return '1.0';
    }

    public static function messageName(): string
    {
        return 'query.product.get_stock';
    }
}

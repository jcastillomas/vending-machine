<?php

declare(strict_types=1);

namespace VM\Context\Product\Application\Query\GetProduct;

use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductName;
use VM\Shared\Application\Bus\Query\Query;

final class GetProductQuery extends Query
{
    private const PRODUCT_NAME = 'product_name';

    public static function create(
        string $productName
    ): self {
        return new self([
            self::PRODUCT_NAME => $productName
        ]);
    }

    public function productName(): ProductName
    {
        return ProductName::fromString($this->get(self::PRODUCT_NAME));
    }

    protected function version(): string
    {
        return '1.0';
    }

    public static function messageName(): string
    {
        return 'query.product.get_product';
    }
}

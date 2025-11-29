<?php

declare(strict_types=1);

namespace VM\Context\Product\Application\Query\GetProduct;


use VM\Context\Product\Domain\Write\Aggregate\Product;

final class GetProductQueryResponseConverter
{
    public function __invoke(Product $product): GetProductQueryResponse
    {
        $result = [
            GetProductQueryResponse::ID => $product->id()->value(),
            GetProductQueryResponse::PRODUCT_NAME => $product->name()->value(),
            GetProductQueryResponse::PRODUCT_VALUE => $product->value()->value(),
        ];

        return new GetProductQueryResponse($result);
    }
}

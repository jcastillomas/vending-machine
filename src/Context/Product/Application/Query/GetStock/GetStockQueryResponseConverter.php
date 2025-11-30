<?php

declare(strict_types=1);

namespace VM\Context\Product\Application\Query\GetStock;

use VM\Context\Product\Domain\Write\Aggregate\Stock;
use VM\Context\Product\Domain\Write\Entity\StockItem;

final class GetStockQueryResponseConverter
{
    public function __invoke(Stock $stock): GetStockQueryResponse
    {
        return new GetStockQueryResponse([
            GetStockQueryResponse::ID => $stock->id()->value(),
            GetStockQueryResponse::VENDING_MACHINE_ID => $stock->vendingMachineId()->value(),
            GetStockQueryResponse::STOCK_ITEMS => array_map($this->buildStockItemFn(), $stock->stockItems()->toArray()),
        ]);
    }

    public function buildStockItemFn(): callable
    {
        return fn (StockItem $stockItem) => [
            GetStockQueryResponse::STOCK_ITEM_ID => $stockItem->id()->value(),
            GetStockQueryResponse::PRODUCT_ID => $stockItem->productId()->value(),
            GetStockQueryResponse::AMOUNT => $stockItem->amount()->value(),
        ];
    }
}

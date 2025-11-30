<?php

declare(strict_types=1);

namespace VM\Context\Product\Application\Query\GetStock;

use VM\Shared\Application\Bus\Query\Response;

final readonly class GetStockQueryResponse implements Response
{
    public const ID = 'id';
    public const VENDING_MACHINE_ID = 'vending_machine_id';
    public const STOCK_ITEMS = 'stock_items';
    public const STOCK_ITEM_ID = 'stock_item_id';
    public const PRODUCT_ID = 'product_id';
    public const AMOUNT = 'amount';

    public function __construct(private array $result)
    {
    }

    public function result(): array
    {
        return $this->result;
    }

    public function id(): string
    {
        return $this->result[self::ID];
    }

    public function vendingMachineId(): string
    {
        return $this->result[self::VENDING_MACHINE_ID];
    }

    public function stockItems(): array
    {
        return $this->result[self::STOCK_ITEMS];
    }
}

<?php

declare(strict_types=1);

namespace VM\Context\Product\Application\Command\UpdateStock;

use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductName;
use VM\Context\Product\Domain\Write\Entity\ValueObject\Amount;
use VM\Shared\Application\Bus\Command\Command;

class UpdateStockCommand extends Command
{
    private const STOCK = 'stock';
    public const STOCK_NAME = 'stock_name';
    public const STOCK_AMOUNT = 'stock_amount';

    public static function create(
        array $stock
    ): self {
        return new self([
            self::STOCK => $stock
        ]);
    }

    public function stock(): array
    {
        return array_map(
            fn (array $stockItem) => [
                self::STOCK_NAME => ProductName::fromString($stockItem[self::STOCK_NAME]),
                self::STOCK_AMOUNT => Amount::fromInt($stockItem[self::STOCK_AMOUNT]),
            ],
            $this->get(self::STOCK)
        );
    }

    public static function messageName(): string
    {
        return 'command.product.update_stock';
    }
}

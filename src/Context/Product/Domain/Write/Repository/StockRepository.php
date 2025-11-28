<?php

declare(strict_types=1);

namespace VM\Context\Product\Domain\Write\Repository;

use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductId;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\StockId;
use VM\Context\Product\Domain\Write\Aggregate\Stock;

interface StockRepository
{
    public function save(Stock $cash): void;
    public function find(StockId $cashId): Stock;
    public function findVendingMachine(): Stock;
    public function findByProductId(ProductId $id): Stock;
}

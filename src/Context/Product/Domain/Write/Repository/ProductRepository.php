<?php

declare(strict_types=1);

namespace VM\Context\Product\Domain\Write\Repository;

use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductId;
use VM\Context\Product\Domain\Write\Aggregate\Product;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductName;

interface ProductRepository
{
    public function save(Product $product): void;
    public function find(ProductId $productId): Product;
    public function findByName(ProductName $productName): Product;
}

<?php

declare(strict_types=1);

namespace VM\Context\Product\Domain\Write\Repository;

use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductId;
use VM\Context\Product\Domain\Write\Aggregate\Product;

interface RepositoryRepository
{
    public function save(Product $currency): void;
    public function find(ProductId $currencyId): Product;
}

<?php

declare(strict_types=1);

namespace VM\Context\Product\Infrastructure\Write\Persistence\Doctrine\MySQL\Repository;

use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductId;
use VM\Context\Product\Domain\Write\Aggregate\Product;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductName;
use VM\Context\Product\Domain\Write\Repository\ProductRepository;
use VM\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;

class DoctrineProductRepository extends AggregateRepository implements ProductRepository
{
    public function save(Product $product): void
    {
        $this->saveAggregate($product);
    }

    public function find(ProductId $productId): Product
    {
        return $this->doFind($productId);
    }

    public function findByName(ProductName $productName): Product
    {
        return $this->doSearchByCriteria(['name' => $productName])[0];
    }

    protected function entityClassName(): string
    {
        return Product::class;
    }
}

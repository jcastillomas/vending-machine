<?php

declare(strict_types=1);

namespace VM\Context\Product\Domain\Write\Aggregate;

use DateTimeImmutable;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductId;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductName;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductValue;
use VM\Shared\Domain\Write\Aggregate\AggregateRoot;

class Product extends AggregateRoot
{
    private ProductName $name;
    private ProductValue $value;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    public static function create(
        ProductId $productId,
        ProductName $productName,
        ProductValue $productValue
    ): self {
        $product =  new self($productId);
        $product->name = $productName;
        $product->value = $productValue;
        $product->createdAt = new DateTimeImmutable();
        $product->updatedAt = null;
        return $product;
    }

    public function name(): ProductName
    {
        return $this->name;
    }

    public function value(): ProductValue
    {
        return $this->value;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}

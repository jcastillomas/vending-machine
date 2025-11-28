<?php

declare(strict_types=1);

namespace VM\Context\Product\Domain\Write\Entity;

use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductId;
use VM\Context\Product\Domain\Write\Entity\ValueObject\Amount;
use VM\Context\Product\Domain\Write\Entity\ValueObject\StockItemId;
use VM\Shared\Domain\Write\Aggregate\Entity;

class StockItem extends Entity
{
    private ProductId $productId;
    private Amount $amount;
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    public static function create(
        StockItemId $id,
        ProductId $productId,
        Amount $amount,
    ): self {
        $stockItem = new self($id);
        $stockItem->productId = $productId;
        $stockItem->amount = $amount;
        $stockItem->createdAt = new \DateTimeImmutable();
        $stockItem->updatedAt = null;
        return $stockItem;
    }

    public function setAmount(Amount $amount)
    {
        $this->amount = $amount;
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function amount(): Amount
    {
        return $this->amount;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}

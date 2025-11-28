<?php

declare(strict_types=1);

namespace VM\Context\Product\Domain\Write\Aggregate;

use DateTimeImmutable;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\StockId;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\VendingMachineId;
use VM\Context\Product\Domain\Write\Entity\StockItems;
use VM\Shared\Domain\Write\Aggregate\AggregateRoot;

class Stock extends AggregateRoot
{
    private VendingMachineId $vendingMachineId;
    private $stockItems;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    public static function create(
        StockId $stockId,
        VendingMachineId $vendingMachineId,
        StockItems $stockItems,
    ): self {
        $stock = new self($stockId);
        $stock->vendingMachineId = $vendingMachineId;
        $stock->stockItems = $stockItems;
        $stock->createdAt = new DateTimeImmutable();
        $stock->updatedAt = null;
        return $stock;
    }

    public function vendingMachineId(): VendingMachineId
    {
        return $this->vendingMachineId;
    }

    public function stockItems()
    {
        return $this->stockItems;
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

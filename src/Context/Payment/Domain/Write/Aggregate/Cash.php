<?php

declare(strict_types=1);

namespace VM\Context\Payment\Domain\Write\Aggregate;

use DateTimeImmutable;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CashId;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\VendingMachineId;
use VM\Context\Payment\Domain\Write\Entity\CashItems;
use VM\Shared\Domain\Write\Aggregate\AggregateRoot;

class Cash extends AggregateRoot
{
    private VendingMachineId $vendingMachineId;
    private $cashItems;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    public static function create(
        CashId $cashId,
        VendingMachineId $vendingMachineId,
        CashItems $cashItems,
    ): self {
        $cash = new self($cashId);
        $cash->vendingMachineId = $vendingMachineId;
        $cash->cashItems = $cashItems;
        $cash->createdAt = new DateTimeImmutable();
        $cash->updatedAt = null;
        return $cash;
    }

    public function vendingMachineId(): VendingMachineId
    {
        return $this->vendingMachineId;
    }

    public function cashItems()
    {
        return $this->cashItems;
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

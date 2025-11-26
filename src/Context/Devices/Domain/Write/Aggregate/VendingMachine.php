<?php

declare(strict_types=1);

namespace VM\Context\Devices\Domain\Write\Aggregate;

use DateTimeImmutable;
use VM\Context\Devices\Domain\Write\Aggregate\ValueObject\VendingMachineId;
use VM\Shared\Domain\Write\Aggregate\AggregateRoot;

class VendingMachine extends AggregateRoot
{
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    public static function create(
        VendingMachineId $vendingMachineId
    ): self {
        $vendingMachine =  new self($vendingMachineId);
        $vendingMachine->createdAt = new DateTimeImmutable();
        $vendingMachine->updatedAt = null;
        return $vendingMachine;
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
